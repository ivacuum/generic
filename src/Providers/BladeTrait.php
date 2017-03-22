<?php namespace Ivacuum\Generic\Providers;

trait BladeTrait
{
    protected function bladeLang()
    {
        \Blade::directive('ru', function ($expression) {
            return '<?php if ($locale === \'ru\'): ?>';
        });

        \Blade::directive('en', function ($expression) {
            return '<?php elseif ($locale === \'en\'): ?>';
        });

        \Blade::directive('de', function ($expression) {
            return '<?php elseif ($locale === \'de\'): ?>';
        });

        \Blade::directive('endlang', function ($expression) {
            return '<?php endif; ?>';
        });
    }

    protected function bladeSvg()
    {
        \Blade::directive('svg', function ($expression) {
            return "<?php require base_path(\"resources/svg/$expression.html\"); ?>";
        });
    }
}
