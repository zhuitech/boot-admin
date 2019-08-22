<?php

namespace ZhuiTech\BootAdmin\Providers;

use Encore\Admin\Grid\Column;
use iBrand\Component\Setting\Models\SystemSetting;
use iBrand\Component\Setting\Repositories\CacheDecorator;
use iBrand\Component\Setting\Repositories\SettingInterface;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Json;
use ZhuiTech\BootAdmin\Console\AdminCommand;
use ZhuiTech\BootAdmin\Console\MenuCommand;
use ZhuiTech\BootAdmin\Console\ServiceCommand;
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
        $this->loadRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([base_path('vendor/overtrue/laravel-ueditor/src/assets/ueditor') => public_path('vendor/ueditor')], 'public');
            $this->publishes([base_path('vendor/encore/laravel-admin/resources/assets') => public_path('vendor/laravel-admin')], 'public');
            $this->publishes([__DIR__ . '/../../resources/assets' => public_path('vendor/boot-admin')], 'public');
            $this->publishes([__DIR__ . '/../../resources/laravel-admin' => public_path('vendor/laravel-admin')], 'public');
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
            AdminCommand::class,
            MenuCommand::class,
            ServiceCommand::class,
        ]);

        // 支持无数据库运行
        $this->app->extend(SettingInterface::class, function ($app) {
            $repository = new SettingRepository(new SystemSetting());
            if (!config('ibrand.setting.cache')) {
                return $repository;
            }
            return new CacheDecorator($repository);
        });

        // Admin Grid 扩展
        Column::extend('json', Json::class);

        parent::register();
    }
}
