<?php namespace Ivacuum\Generic\Utilities;

class TextImagesParser
{
    public function parse(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = trim($text, "\n");

        $images = $result = [];

        // Картинки обрабатываются на строку позже, поэтому в конец текста добавлен \n
        foreach (explode("\n", $text."\n") as $line) {
            if (preg_match('#^(https?:\/\/[A-Za-z-_\d\/\.]+\.(jpe?g|png))$#', $line, $matches)) {
                $images[] = $matches[1];
            } else {
                $sizeof = sizeof($images);

                if ($sizeof > 1) {
                    $this->fotoramaMarkup($result, $images);
                } elseif ($sizeof === 1) {
                    $this->singleImageMarkup($result, $images[0]);
                }

                $images = [];

                $result[] = $line;
            }
        }

        return implode("\n", $result);
    }

    protected function fotoramaMarkup(array &$result, array $images): void
    {
        $result[] = '<div class="tw--mt-2 tw-mb-6 tw-mobile-wide js-shortcuts-item">';
        $result[] = '<div class="tw-max-w-1000px tw-mx-auto tw-text-center">';

        $i = 0;
        $lastIteration = sizeof($images) - 1;

        foreach ($images as $image) {
            $lastImageClass = $i === $lastIteration ? 'sm:tw-rounded-b' : '';
            $firstImageClass = $i === 0 ? 'sm:tw-rounded-t' : '';

            $result[] = '<div><img class="markdown-responsive-image '."{$firstImageClass} {$lastImageClass}".' js-lazy" alt="" src="https://life.ivacuum.ru/0.gif" data-src="'.$image.'"></div>';

            $i++;
        }

        $result[] = '</div>';
        $result[] = '</div>';
    }

    protected function singleImageMarkup(array &$result, string $image): void
    {
        $result[] = '<div class="tw--mt-2 tw-mb-6 tw-mobile-wide js-shortcuts-item">';
        $result[] = '<div class="tw-max-w-1000px tw-mx-auto tw-text-center">';

        $result[] = '<img class="markdown-responsive-image sm:tw-rounded js-lazy" alt="" src="https://life.ivacuum.ru/0.gif" data-src="'.$image.'">';

        $result[] = '</div>';
        $result[] = '</div>';
    }
}
