<?php namespace Ivacuum\Generic\Utilities;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\HtmlString;

class ViewHelper
{
    protected $decimal;

    public function __construct()
    {
        $this->decimal = new \NumberFormatter('ru_RU', \NumberFormatter::DECIMAL);
        $this->decimal->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        $this->decimal->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '&nbsp;');
    }

    public function avatarBg($id)
    {
        return config('cfg.avatar_bg')[$id % 15];
    }

    /**
     * @param  \Illuminate\Support\Carbon|null $date
     * @return string
     */
    public function dateShort($date)
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

    public function inputHiddenMail()
    {
        return new HtmlString('<input hidden type="text" name="mail" value="'.old("mail").'">');
    }

    public function metaTitle($meta_title, $view)
    {
        if ($meta_title) {
            return $meta_title;
        }

        if (trans("meta_title.{$view}") !== "meta_title.{$view}") {
            return trans("meta_title.{$view}");
        }

        if (trans($view) !== $view) {
            return trans($view);
        }

        return config('cfg.sitename');
    }

    public function modelFieldTrans($model, $field)
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

    public function number($number)
    {
        return $this->decimal->format($number);
    }

    public function paginatorIteration($paginator, $loop)
    {
        $page = $per_page = 0;

        if ($paginator instanceof LengthAwarePaginator) {
            $page = $paginator->currentPage() - 1;
            $per_page = $paginator->perPage();
        }

        return $page * $per_page + $loop->iteration;
    }

    public function plural($key, $count)
    {
        return trans_choice("plural.{$key}", $count, ['x' => $this->number($count)]);
    }

    public function size($bytes)
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

        return round($bytes, $decimals[$pow]) . '&nbsp;' . $units[$pow];
    }
}
