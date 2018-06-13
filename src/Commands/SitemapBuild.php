<?php namespace Ivacuum\Generic\Commands;

class SitemapBuild extends Command
{
    protected $signature = 'app:sitemap-build {threshold=50000}';
    protected $description = 'Build sitemap.xml';

    protected $now;
    protected $count = 0;
    protected $pages = [];
    protected $prefix;
    protected $multiple = false;
    protected $threshold = 50000;

    public function handle()
    {
        $this->init();
        $this->pages();
        $this->write();

        if ($this->multiple) {
            $this->writeSitemapIndex();
        }

        $this->move();
    }

    protected function incrementCounter(): void
    {
        $this->count++;

        if (!$this->multiple && $this->count >= $this->threshold) {
            $this->multiple = true;
        }
    }

    protected function init(): void
    {
        $this->purge();

        $this->now = now()->toDateString();
        $this->prefix = url('');
        $this->threshold = $this->argument('threshold');
    }

    protected function move(): void
    {
        $touched = ['sitemap.xml'];

        /* Перенос сформированных файлов */
        rename(public_path('uploads/temp/sitemap.xml'), public_path('uploads/sitemaps/sitemap.xml'));

        foreach (glob(public_path('uploads/temp/sitemap-*.xml.gz')) as $filepath) {
            $filename = basename($filepath);

            rename($filepath, public_path("uploads/sitemaps/{$filename}"));

            $touched[] = $filename;
        }

        foreach (glob(public_path('uploads/sitemaps/sitemap*')) as $filepath) {
            if (!in_array(basename($filepath), $touched)) {
                unlink($filepath);
            }
        }
    }

    protected function page($locs, $priorities = "1", string $changefreq = 'daily', string $lastmod = ''): void
    {
        foreach (array_wrap($locs) as $loc) {
            $loc = "{$this->prefix}/{$loc}";
            $lastmod = $lastmod ?: $this->now;
            $priority = is_array($priorities) ? array_random($priorities) : $priorities;

            $this->pages[] = compact('loc', 'lastmod', 'changefreq', 'priority');

            $this->incrementCounter();

            if ($this->count % $this->threshold === 0) {
                $this->write();
            }
        }
    }

    protected function pages()
    {
        $this->page('');
    }

    protected function purge()
    {
        @unlink(public_path('uploads/temp/sitemap.xml'));

        foreach (glob(public_path('uploads/temp/sitemap-*.xml.gz')) as $filename) {
            unlink($filename);
        }
    }

    /**
     * @return bool|resource
     */
    protected function sitemapToStream()
    {
        $stream = fopen('php://memory', 'r+');

        fwrite($stream, '<?xml version="1.0" encoding="UTF-8"?>'."\n");
        fwrite($stream, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n");

        foreach ($this->pages as $page) {
            fwrite(
                $stream,
                sprintf(
                    '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%s</priority></url>',
                    $page['loc'],
                    $page['lastmod'],
                    $page['changefreq'],
                    $page['priority']
                )
            );
        }

        fwrite($stream, '</urlset>');

        $this->pages = [];

        return $stream;
    }

    protected function write(): void
    {
        if (!sizeof($this->pages)) {
            return;
        }

        $this->multiple
            ? $this->writePartialSitemap()
            : $this->writeSingleSitemap();
    }

    protected function writePartialSitemap(): void
    {
        $stream = $this->sitemapToStream();

        rewind($stream);

        $part = ceil($this->count / $this->threshold);

        file_put_contents('compress.zlib://'.public_path("uploads/temp/sitemap-{$part}.xml.gz"), $stream);
    }

    protected function writeSingleSitemap(): void
    {
        \Storage::disk('temp')->put('sitemap.xml', $this->sitemapToStream());
    }

    protected function writeSitemapIndex(): void
    {
        $parts = round($this->count / $this->threshold);
        $stream = fopen('php://memory', 'r+');

        fwrite($stream, '<?xml version="1.0" encoding="UTF-8"?>'."\n");
        fwrite($stream, '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n");

        foreach (range(1, $parts) as $part) {
            fwrite(
                $stream,
                sprintf(
                    '<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>',
                    "{$this->prefix}/uploads/sitemaps/sitemap-{$part}.xml.gz",
                    $this->now
                )
            );
        }

        fwrite($stream, '</sitemapindex>');

        \Storage::disk('temp')->put('sitemap.xml', $stream);
    }
}
