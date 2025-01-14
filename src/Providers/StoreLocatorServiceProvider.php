<?php

namespace Otomaties\StoreLocator\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Otomaties\StoreLocator\Helpers\Assets;
use Otomaties\StoreLocator\Helpers\Loader;
use Otomaties\StoreLocator\Modules\Frontend;

class StoreLocatorServiceProvider extends ServiceProvider
{
    private array $modules = [
        Frontend::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/store-locator.php',
            'store-locator'
        );

        $this->app->bind(Assets::class, function () {
            return new Assets(config('store-locator.paths.public'). '/build');
        });

        $this->app->singleton(Loader::class, function () {
            return new Loader();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(
            __DIR__.'/../../resources/views',
            'StoreLocator',
        );

        $this->loadModules();
        $this->initPostTypes();
        $this->initFields();
        $this->initShortcodes();

        $this->app->make(Loader::class)->run();
    }

    private function initPostTypes() : void
    {
        collect([
            'PostTypes',
            'Taxonomies',
        ])->each(function ($registerableClassPath) {
            $this
                ->collectFilesIn("$registerableClassPath")
                ->each(function ($filename) {
                    add_action('init', function () use ($filename) {
                        $className = $this->namespacedClassNameFromFilename($filename);
                        (new $className())
                        ->register();
                    });
                });
        });
    }

    private function initFields() : void
    {
        $this
            ->collectFilesIn('/Fields')
            ->each(function ($filename) {
                add_action('acf/init', function () use ($filename) {
                    $className = $this->namespacedClassNameFromFilename($filename);
                    (new $className())
                    ->register();
                });
            });
    }

    private function initShortcodes() : void
    {
        $this
            ->collectFilesIn('/Shortcodes')
            ->each(function ($filename) {
                $className = $this->namespacedClassNameFromFilename($filename);
                add_shortcode($className::SHORTCODE_NAME, [new $className, 'callback']);
            });
    }

    private function loadModules() : void
    {
        collect($this->modules)
            ->each(function ($className) {
                ($this->app->make($className))
                    ->init();
            });
    }

    private function collectFilesIn($path) : Collection
    {
        $fullPath = config('store-locator.paths.src') . "/$path";

        return collect(array_merge(
            glob("$fullPath/*.php"),
            glob("$fullPath/**/*.php")
        ))
        ->reject(function ($filename) {
            return Str::contains($filename, 'Example');
        })
        ->reject(function ($filename) {
            return Str::contains($filename, '/Abstracts') || Str::contains($filename, '/Traits') || Str::contains($filename, '/Contracts');
        });
    }

    private function namespacedClassNameFromFilename($filename)
    {
        $namespace = str_replace('\Providers', '', __NAMESPACE__);

        return Str::of($filename)
            ->replace(config('store-locator.paths.src'), '')
            ->ltrim('/')
            ->replace('/', '\\')
            ->rtrim('.php')
            ->prepend('\\' . $namespace . '\\')
            ->__toString();
    }
}
