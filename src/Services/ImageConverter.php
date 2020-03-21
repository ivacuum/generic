<?php namespace Ivacuum\Generic\Services;

use Illuminate\Http\UploadedFile;

/**
 * Конвертер изображений с помощью библиотеки GraphicsMagick
 */
class ImageConverter
{
    protected $authOrient;
    protected $crop;
    protected $filter;
    protected $filters = ['point', 'box', 'triangle', 'hermite', 'hanning', 'hamming', 'blackman', 'gaussian', 'quadratic', 'cubic', 'catrom', 'mitchell', 'lanczos', 'bessel', 'sinc'];
    protected $firstFrame;
    protected $gravity;
    protected $gravities = ['northwest', 'north', 'northeast', 'west', 'center', 'east', 'southwest', 'south', 'southeast'];
    protected $quality;
    protected $resize;
    protected $size;

    /**
     * Автоматическое определение ориентации снимка
     *
     * @return $this
     */
    public function autoOrient()
    {
        $this->authOrient = '-auto-orient';

        return $this;
    }

    /**
     * Параметры нужно внимательно экранировать, например, \\>
     *
     * Но: звездочку +profile "*" экранировать не нужно, иначе в изображении останутся профили
     *
     * @param string $source Путь к исходному файлу
     * @return \Illuminate\Http\UploadedFile
     */
    public function convert(string $source): UploadedFile
    {
        $destination = $this->tempFile();

        $command = implode(
            ' ',
            [
                config('cfg.gm_bin'),
                'convert',
                $this->size,
                escapeshellarg($this->source($source)),
                $this->authOrient,
                $this->quality,
                $this->filter, // Фильтр должен быть перед resize
                $this->resize,
                $this->gravity,
                $this->crop,
                '+profile "*"',
                escapeshellarg($destination),
            ]
        );

        passthru($command);

        if (!file_exists($destination)) {
            throw new \Exception('Преобразование файла не удалось');
        }

        return new UploadedFile($destination, basename($source));
    }

    public function crop(int $width, int $height): self
    {
        $this->crop = "-crop {$width}x{$height}+0+0";
        $this->gravity('center');
        $this->resize($width, $height, '^');

        return $this;
    }

    /**
     * Фильтр для размыливания
     *
     * @param string $filter
     * @return $this
     */
    public function filter(string $filter): self
    {
        if (!in_array($filter, $this->filters)) {
            throw new \Exception("Фильтр [{$filter}] не найден");
        }

        $this->filter = "-filter {$filter}";

        return $this;
    }

    /**
     * Первый кадр gif для миниатюры
     *
     * @return $this
     */
    public function firstFrame(): self
    {
        $this->firstFrame = true;

        return $this;
    }

    public function gravity(string $gravity): self
    {
        $gravity = strtolower($gravity);

        if (!in_array($gravity, $this->gravities)) {
            throw new \Exception("Фильтр [{$gravity}] не найден");
        }

        $this->gravity = "-gravity {$gravity}";

        return $this;
    }

    public function resize(int $width, int $height, string $mark = '>'): self
    {
        $this->size = "-size {$width}x{$height}";
        $this->resize = "-resize '{$width}x{$height}{$mark}'";

        return $this;
    }

    /**
     * Путь к файлу-исходнику. Для gif берется первый кадр
     *
     * @param string $source
     * @return string
     */
    public function source(string $source): string
    {
        if ($this->firstFrame) {
            return "{$source}[0]";
        }

        return $source;
    }

    public function quality(int $quality): self
    {
        $this->quality = "-quality {$quality}"; // 1-100

        return $this;
    }

    /**
     * Результат работы конвертера будет помещен во временный файл, который будет удален по завершении запроса
     * Временный файл после преобразований подразумевается перенести в постоянное хранилище
     *
     * @return string
     */
    protected function tempFile(): string
    {
        $filename = \Str::random(6);
        $destination = storage_path("app/resize-{$filename}");

        register_shutdown_function(
            function () use ($destination) {
                unlink($destination);
            }
        );

        return $destination;
    }
}
