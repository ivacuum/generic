<?php namespace Ivacuum\Generic\Utilities;

class TextImagesParser
{
    public function parse($text)
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

    protected function fotoramaMarkup(&$result, array $images)
    {
        $result[] = '<div class="pic-container shortcuts-item">';
        $result[] = '<div class="pic-centered-container">';
        $result[] = '<div class="js-lazy" data-lazy-type="fotorama">';

        foreach ($images as $image) {
            $result[] = '<a hidden href="'.$image.'"></a>';
        }

        $result[] = '</div>';
        $result[] = '</div>';
        $result[] = '</div>';
    }

    protected function singleImageMarkup(&$result, $image)
    {
        $result[] = '<div class="pic-container shortcuts-item">';
        $result[] = '<div class="pic-centered-container">';

        $result[] = '<img class="js-lazy markdown-responsive-image" src="https://life.ivacuum.ru/0.gif" data-src="'.$image.'">';

        $result[] = '</div>';
        $result[] = '</div>';
    }
}
