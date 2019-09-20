<?php

namespace ZhuiTech\BootAdmin\Providers;

use Encore\Admin\Form;
use Encore\Admin\Grid\Column;
use Encore\Admin\Show;
use iBrand\Component\Setting\Models\SystemSetting;
use iBrand\Component\Setting\Repositories\CacheDecorator;
use iBrand\Component\Setting\Repositories\SettingInterface;
use Illuminate\Support\Arr;
use ZhuiTech\BootAdmin\Admin\Form\Fields\CKEditor;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Image;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Json;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Yuan;
use ZhuiTech\BootAdmin\Console\AdminCommand;
use ZhuiTech\BootAdmin\Console\MenuCommand;
use ZhuiTech\BootAdmin\Console\ServiceCommand;
use ZhuiTech\BootAdmin\Models\Staff;
use ZhuiTech\BootAdmin\Repositories\SettingRepository;
use ZhuiTech\BootLaravel\Providers\AbstractServiceProvider;

class AdminServiceProvider extends AbstractServiceProvider
{
    protected $commands = [
        AdminCommand::class,
        MenuCommand::class,
        ServiceCommand::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app('view')->prependNamespace('admin', __DIR__ . '/../../resources/views');
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
        // 支持无数据库运行
        $this->app->extend(SettingInterface::class, function ($app) {
            $repository = new SettingRepository(new SystemSetting());
            if (!config('ibrand.setting.cache')) {
                return $repository;
            }
            return new CacheDecorator($repository);
        });

        // Admin 扩展
        Column::extend('yuan', Yuan::class);
        Column::extend('json', Json::class);
        Column::extend('image', Image::class);
        Form::extend('editor', CKEditor::class);
        Show::extend('yuan', \ZhuiTech\BootAdmin\Admin\Show\Yuan::class);
        Show::extend('array', \ZhuiTech\BootAdmin\Admin\Show\JsonArray::class);

        // 员工
        $auth = [
            'guards' => [
                'staff' => [
                    'driver' => 'passport',
                    'provider' => 'staff',
                    'hash' => false,
                ],
            ],
            'providers' => [
                'staff' => [
                    'driver' => 'eloquent',
                    'model'  => Staff::class,
                ],
            ],
        ];
        config(Arr::dot($auth, 'auth.'));
        
        parent::register();
    }
}
