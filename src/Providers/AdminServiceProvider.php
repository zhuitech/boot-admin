<?php

namespace ZhuiTech\BootAdmin\Providers;

use iBrand\Component\Setting\Models\SystemSetting;
use iBrand\Component\Setting\Repositories\CacheDecorator;
use iBrand\Component\Setting\Repositories\SettingInterface;
use ZhuiTech\BootAdmin\Console\InitialCommand;
use ZhuiTech\BootAdmin\Console\MenuRebuildCommand;
use ZhuiTech\BootAdmin\Repositories\SettingRepository;
use ZhuiTech\BootLaravel\Providers\AbstractServiceProvider;

class AdminServiceProvider extends AbstractServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(base_path('vendor/encore/laravel-admin/database/migrations'));
        $this->loadMigrations();

        if ($this->app->runningInConsole()) {
            $this->publishes([base_path('vendor/encore/laravel-admin/resources/assets') => public_path('vendor/laravel-admin')], 'public');
            $this->publishes([__DIR__ . '/../../resources/assets' => public_path('vendor/boot-admin')], 'public');
        }

        parent::boot();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
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

        parent::register();
    }
}
