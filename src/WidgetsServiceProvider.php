<?php

namespace Imanghafoori\Widgets;

use Illuminate\Support\ServiceProvider;
use Imanghafoori\Widgets\Utils\Normalizers\CacheNormalizer;
use Imanghafoori\Widgets\Utils\Normalizers\ControllerNormalizer;
use Imanghafoori\Widgets\Utils\Normalizers\PresenterNormalizer;
use Imanghafoori\Widgets\Utils\Normalizers\TemplateNormalizer;

class WidgetsServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('include_widget', function ($expression) {
            return "<?php echo $expression; ?>";
        });

        $this->loadViewsFrom($this->app->basePath() . '/app/Widgets/', 'Widgets');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.imanghafoori.widget', function ($app) {
            return $app['Imanghafoori\Widgets\WidgetGenerator'];
        });

        $this->app->singleton('imanghafoori.widget.normalizer', function () {
            $cacheNormalizer = new CacheNormalizer();
            $templateNormalizer = new TemplateNormalizer();
            $presenterNormalizer = new PresenterNormalizer();
            $controllerNormalizer = new ControllerNormalizer();

            return new Utils\Normalizer($templateNormalizer, $cacheNormalizer, $presenterNormalizer, $controllerNormalizer);
        });

        $this->app->singleton('imanghafoori.widget.minifier', function () {
            return new Utils\HtmlMinifier();
        });

        $this->app->singleton('imanghafoori.widget.debugInfo', function () {
            return new Utils\DebugInfo();
        });

        $this->app->singleton('imanghafoori.widget.policies', function () {
            return new Utils\Policies();
        });

        $this->app->singleton('imanghafoori.widget.cache', function () {
            return new Utils\Cache();
        });

        $this->app->singleton('imanghafoori.widget.renderer', function () {
            return new Utils\WidgetRenderer();
        });

        $this->commands('command.imanghafoori.widget');
    }
}
