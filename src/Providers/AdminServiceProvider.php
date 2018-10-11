<?php

namespace ZhuiTech\BootAdmin\Providers;

use Encore\Admin\Admin;
use Illuminate\Support\ServiceProvider;
use ZhuiTech\BootAdmin\Console\Clear;
use ZhuiTech\BootAdmin\Console\Devel;

class AdminServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app('view')->prependNamespace('admin', __DIR__ . '/../../resources/views/admin');

        $this->loadMigrationsFrom(base_path('vendor/ibrand/backend/migrations'));
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Clear::class,
            Devel::class
        ]);
    }
}
