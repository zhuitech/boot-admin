<?php

namespace ZhuiTech\BootAdmin\Providers;

use Encore\Admin\Admin;
use Illuminate\Support\ServiceProvider;
use ZhuiTech\BootAdmin\Console\ClearCommand;
use ZhuiTech\BootAdmin\Console\DevelCommand;
use ZhuiTech\BootAdmin\Console\InitialCommand;
use ZhuiTech\BootAdmin\Console\MenuRebuildCommand;
use iBrand\Component\Setting\Models\SystemSetting;
use iBrand\Component\Setting\Repositories\CacheDecorator;
use iBrand\Component\Setting\Repositories\SettingInterface;
use ZhuiTech\BootAdmin\Repositories\SettingRepository;

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

        $this->loadMigrationsFrom(base_path('vendor/encore/laravel-admin/database/migrations'));

        $this->loadMigrationsFrom(base_path('vendor/ibrand/backend/migrations'));
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([base_path('vendor/ibrand/backend/resources/assets') => public_path('vendor')], 'public');
            $this->publishes([base_path('vendor/encore/laravel-admin/resources/assets') => public_path('vendor/laravel-admin')], 'public');
            $this->publishes([__DIR__ . '/../../resources/assets' => public_path('vendor/boot-admin')], 'public');
            $this->publishes([__DIR__ . '/../../overrides/assets' => public_path('vendor')], 'public');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            DevelCommand::class,
            InitialCommand::class,
            MenuRebuildCommand::class,
        ]);

        // 支持无数据库运行
        $this->app->extend(SettingInterface::class, function ($app) {
            $repository = new SettingRepository(new SystemSetting());
            if (!config('ibrand.setting.cache')) {
                return $repository;
            }
            return new CacheDecorator($repository);
        });
    }
}
