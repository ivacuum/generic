<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->lang();
        $this->prop();
        $this->svg();
    }

    protected function lang()
    {
        \Blade::directive('ru', function () {
            return '<?php if ($locale === \'ru\'): ?>';
        });

        \Blade::directive('endru', function () {
            return '<?php endif; ?>';
        });

        \Blade::directive('en', function () {
            return '<?php elseif ($locale === \'en\'): ?>';
        });

        \Blade::directive('enden', function () {
            return '<?php endif; ?>';
        });

        \Blade::directive('de', function () {
            return '<?php elseif ($locale === \'de\'): ?>';
        });

        \Blade::directive('endde', function () {
            return '<?php endif; ?>';
        });

        \Blade::directive('endlang', function () {
            return '<?php endif; ?>';
        });
    }

    protected function prop()
    {
        \Blade::directive('prop', function ($expression) {
            return '<?php echo \Ivacuum\Generic\Utilities\Vue::prop($expression); ?>';
        });
    }

    protected function svg()
    {
        \Blade::directive('svg', function ($expression) {
            return "<?php require base_path(\"resources/svg/$expression.svg\"); ?>";
        });
    }
}
