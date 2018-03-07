<?php namespace Ivacuum\Generic\Utilities;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\HtmlString;
use Ivacuum\Generic\Rules\ConcurrencyControl;

class ViewHelper
{
    protected static $thousands_separator = '&thinsp;';

    protected $decimal;

    public function __construct()
    {
        $this->decimal = new \NumberFormatter('ru_RU', \NumberFormatter::DECIMAL);
        $this->decimal->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        $this->decimal->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, static::$thousands_separator);
    }

    public function avatarBg($id): string
    {
        return config('cfg.avatar_bg')[$id % 15];
    }

    /**
     * @param  \Illuminate\Support\Carbon|null $date
     * @return string
     */
    public function dateShort($date): string
    {
        static $year;

        if (is_null($date)) {
            return '—';
        }

        if (empty($year)) {
            $year = now()->year;
        }

        if ($date->year === $year) {
            return $date->formatLocalized('%e %B');
        }

        return $date->formatLocalized('%e %b %Y');
    }

    public function inputHiddenConcurrencyControl($value)
    {
        return new HtmlString('<input hidden type="text" name="'.ConcurrencyControl::FIELD.'" value="'.md5($value).'">');
    }

    public function inputHiddenMail()
    {
        return new HtmlString('<input hidden type="text" name="mail" value="'.old("mail").'">');
    }

    public function metaDescription(string $description, string $view, array $replace = []): string
    {
        if ($description) {
            return $description;
        }

        if (trans("meta_description.{$view}") !== "meta_description.{$view}") {
            return trans("meta_description.{$view}", $replace);
        }

        return '';
    }

    public function metaKeywords(string $keywords, string $view, array $replace = []): string
    {
        if ($keywords) {
            return $keywords;
        }

        if (trans("meta_keywords.{$view}") !== "meta_keywords.{$view}") {
            return trans("meta_keywords.{$view}", $replace);
        }

        return '';
    }

    public function metaTitle(string $title, string $view, array $replace = []): string
    {
        if ($title) {
            return $title;
        }

        if (trans("meta_title.{$view}") !== "meta_title.{$view}") {
            return trans("meta_title.{$view}", $replace);
        }

        if (trans($view) !== $view) {
            return trans($view);
        }

        return config('cfg.sitename');
    }

    public function modelFieldTrans(string $model, string $field): string
    {
        $trans_key = "model.$model.$field";

        if (($text = trans($trans_key)) !== $trans_key) {
            return $text;
        }

        $trans_key_general = "model.$field";

        if (($text = trans($trans_key_general)) !== $trans_key_general) {
            return $text;
        }

        return $trans_key;
    }

    public function number(int $number): string
    {
        return $this->decimal->format($number);
    }

    public function numberShort(int $number): string
    {
        $units = ['', 'K', 'M'];
        $decimals = [0, 0, 0];

        $number = max($number, 0);
        $pow = floor(($number ? log($number) : 0) / log(1024));
        $pow = min($pow, sizeof($units) - 1);
        $number /= pow(1024, $pow);

        return round($number, $decimals[$pow]).$units[$pow];
    }

    public function paginatorIteration($paginator, $loop): int
    {
        $page = $per_page = 0;

        if ($paginator instanceof LengthAwarePaginator) {
            $page = $paginator->currentPage() - 1;
            $per_page = $paginator->perPage();
        }

        return $page * $per_page + $loop->iteration;
    }

    public function plural(string $key, int $count): string
    {
        return trans_choice("plural.{$key}", $count, ['x' => $this->number($count)]);
    }

    public function size(int $bytes): string
    {
        $units = [
            trans('size.b'),
            trans('size.kb'),
            trans('size.mb'),
            trans('size.gb'),
            trans('size.tb'),
        ];

        $decimals = [0, 0, 1, 1, 1];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, sizeof($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $decimals[$pow]) . static::$thousands_separator . $units[$pow];
    }
}
