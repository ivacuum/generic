<?php namespace Ivacuum\Generic\Utilities;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\HtmlString;
use Ivacuum\Generic\Rules\ConcurrencyControl;

class ViewHelper
{
    protected static $thousandsSeparator = '&thinsp;';

    public function avatarBg(int $id): string
    {
        return config('cfg.avatar_bg')[$id % sizeof(config('cfg.avatar_bg'))];
    }

    public function dateShort(?CarbonInterface $date): string
    {
        static $year;

        if (null === $date) {
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

    public function metaDescription(string $view, array $replace = []): string
    {
        if (trans("meta_description.{$view}") !== "meta_description.{$view}") {
            return trans("meta_description.{$view}", $replace);
        }

        return '';
    }

    public function metaKeywords(string $view, array $replace = []): string
    {
        if (trans("meta_keywords.{$view}") !== "meta_keywords.{$view}") {
            return trans("meta_keywords.{$view}", $replace);
        }

        return '';
    }

    public function metaTitle(string $view, array $replace = []): string
    {
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
        $transKey = "model.$model.$field";

        if (($text = trans($transKey)) !== $transKey) {
            return $text;
        }

        $transKeyGeneral = "model.$field";

        if (($text = trans($transKeyGeneral)) !== $transKeyGeneral) {
            return $text;
        }

        return $transKey;
    }

    public function number(int $number): string
    {
        $decimal = new \NumberFormatter('ru_RU', \NumberFormatter::DECIMAL);
        $decimal->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        $decimal->setSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, static::$thousandsSeparator);

        return $decimal->format($number);
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
        $page = $perPage = 0;

        if ($paginator instanceof LengthAwarePaginator) {
            $page = $paginator->currentPage() - 1;
            $perPage = $paginator->perPage();
        }

        return $page * $perPage + $loop->iteration;
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

        return round($bytes, $decimals[$pow]) . static::$thousandsSeparator . $units[$pow];
    }
}
