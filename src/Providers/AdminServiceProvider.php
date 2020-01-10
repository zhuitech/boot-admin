<?php

namespace ZhuiTech\BootAdmin\Providers;

use Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Navbar\Fullscreen;
use Illuminate\Support\Arr;
use ZhuiTech\BootAdmin\Admin\Extensions\Actions\ClearCache;
use ZhuiTech\BootAdmin\Admin\Extensions\Nav\AutoRefresh;
use ZhuiTech\BootAdmin\Admin\Extensions\Nav\Link;
use ZhuiTech\BootAdmin\Admin\Form\Fields\CKEditor;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Admin as AdminUser;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Image;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Json;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\RemoteUser;
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
            $this->publishes([base_path('vendor/laravel-admin-ext/chartjs/resources/assets') => public_path('vendor/laravel-admin-ext/chartjs')], 'public');
            $this->publishes([__DIR__ . '/../../resources/assets' => public_path('vendor/boot-admin')], 'public');
            $this->publishes([__DIR__ . '/../../resources/laravel-admin' => public_path('vendor/laravel-admin')], 'public');
        }

        $this->configAdmin();
        $this->loadSettings();

        parent::boot();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->configStuff();
        
        parent::register();
    }

    /**
     * 配置后台
     */
    private function configAdmin()
    {
        // Admin 扩展
        Column::extend('yuan', Yuan::class);
        Column::extend('json', Json::class);
        Column::extend('image', Image::class);
        Column::extend('admin', AdminUser::class);
        Column::extend('remoteUser', RemoteUser::class);
        
        Form::extend('editor', CKEditor::class);
        
        Show::extend('yuan', \ZhuiTech\BootAdmin\Admin\Show\Yuan::class);
        Show::extend('array', \ZhuiTech\BootAdmin\Admin\Show\JsonArray::class);

        Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
            $navbar->left(view('admin::partials.topbar-left'));
            $navbar->right(view('admin::partials.topbar-right'));

            $navbar->right(Link::make('设置', 'system/settings', 'fa-cog'));
            $navbar->right(new ClearCache());
            $navbar->right(new Fullscreen());
            $navbar->right(new AutoRefresh());
        });
    }

    /**
     * 配置员工
     */
    private function configStuff()
    {
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
    }

    /**
     * 加载设置
     */
    private function loadSettings()
    {
        $settings = [];
        foreach (config('backend.settings') as $item) {
            $value = settings($item);
            if (!empty($value)) {
                $settings[$item] = $value;
            }
        }
        config($settings);
    }
}
