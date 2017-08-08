<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->lang();
        $this->prop();
        $this->svg();
    }

    protected function lang()
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
