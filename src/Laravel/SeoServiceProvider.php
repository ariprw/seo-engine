<?php
namespace Ari\SeoEngine\Laravel;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/seo.php', 'seo');

        $this->app->singleton('seo', function($app) {
            return new \Ari\SeoEngine\Core\Engine\SeoEngine();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/seo.php' => config_path('seo.php'),
        ], 'config');
    }
}