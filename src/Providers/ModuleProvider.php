<?php

namespace TypiCMS\Modules\Files\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Shells\Observers\FileObserver;
use TypiCMS\Modules\Core\Shells\Services\Cache\LaravelCache;
use TypiCMS\Modules\Files\Shells\Models\File;
use TypiCMS\Modules\Files\Shells\Repositories\CacheDecorator;
use TypiCMS\Modules\Files\Shells\Repositories\EloquentFile;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.files'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['files' => []], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'files');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'files');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/files'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Files',
            'TypiCMS\Modules\Files\Shells\Facades\Facade'
        );

        // Observers
        File::observe(new FileObserver());
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Files\Shells\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Files\Shells\Composers\SidebarViewComposer');

        $app->bind('TypiCMS\Modules\Files\Shells\Repositories\FileInterface', function (Application $app) {
            $repository = new EloquentFile(new File());
            if (!config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['files', 'galleries'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
