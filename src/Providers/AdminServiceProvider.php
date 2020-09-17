<?php

namespace ZhuiTech\BootAdmin\Providers;

use Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid\Column;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Navbar;
use Encore\Admin\Widgets\Navbar\Fullscreen;
use Encore\LargeFileUpload\LargeFileField;
use Illuminate\Support\Arr;
use ZhuiTech\BootAdmin\Admin\Extensions\Actions\ClearCache;
use ZhuiTech\BootAdmin\Admin\Extensions\Nav\AutoRefresh;
use ZhuiTech\BootAdmin\Admin\Extensions\Nav\Link;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Admin as AdminUser;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Call;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Edit;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Image;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Json;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\LargeFile;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\RemoteUser;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Route;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Thumbnail;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Timestamp;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Yuan;
use ZhuiTech\BootAdmin\Admin\Menu\AdminMenuFacade;
use ZhuiTech\BootAdmin\Admin\Show\JsonArray;
use ZhuiTech\BootAdmin\Console\AdminCommand;
use ZhuiTech\BootAdmin\Console\ExportMenuCommand;
use ZhuiTech\BootAdmin\Console\ServiceCommand;
use ZhuiTech\BootAdmin\Models\Staff;
use ZhuiTech\BootLaravel\Providers\AbstractServiceProvider;

class AdminServiceProvider extends AbstractServiceProvider
{
	protected $commands = [
		AdminCommand::class,
		ServiceCommand::class,
		ExportMenuCommand::class,
	];

	protected $facades = [
		'AdminMenu' => AdminMenuFacade::class
	];

	protected $providers = [
		HorizonServiceProvider::class
	];

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		app('view')->prependNamespace('admin', __DIR__ . '/../../resources/views');

		$this->loadRoutes();

		if ($this->app->runningInConsole()) {
			$this->publishes([base_path('vendor/encore/laravel-admin/resources/assets') => public_path('vendor/laravel-admin')], 'public');
			$this->publishes([base_path('vendor/laravel-admin-ext/chartjs/resources/assets') => public_path('vendor/laravel-admin-ext/chartjs')], 'public');
			$this->publishes([base_path('vendor/dianwoung/large-file-upload/resources/assets') => public_path('vendor/laravel-admin-ext/large-file-upload')], 'public');
			$this->publishes([base_path('vendor/peinhu/aetherupload-laravel/assets') => public_path('vendor/aetherupload/js')], 'public');
			$this->publishes([base_path('vendor/ghost/ckeditor/resources/assets') => public_path('vendor/ghost/ckeditor')], 'public');
			$this->publishes([base_path('vendor/laravel/horizon/public') => public_path('vendor/horizon')], 'public');

			$this->publishes([__DIR__ . '/../../resources/assets' => public_path('vendor/boot-admin')], 'public');
			$this->publishes([__DIR__ . '/../../resources/laravel-admin' => public_path('vendor/laravel-admin')], 'public');
		}

		$this->configAdmin();
		$this->loadMigrations();

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
		$this->configHorizon();

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
		Column::extend('timestamp', Timestamp::class);
		Column::extend('thumbnail', Thumbnail::class);
		Column::extend('edit', Edit::class);
		Column::extend('call', Call::class);
		Column::extend('largefile', LargeFile::class);
		Column::extend('route', Route::class);

		//Form::extend('editor', CKEditor::class);
		Form::extend('editor', \ghost\CKEditor\CKEditor::class);
		Form::extend('largefile', LargeFileField::class);

		Show::extend('yuan', \ZhuiTech\BootAdmin\Admin\Show\Yuan::class);
		Show::extend('array', JsonArray::class);
		Show::extend('timestamp', \ZhuiTech\BootAdmin\Admin\Show\Timestamp::class);

		// 后台导航条
		Admin::navbar(function (Navbar $navbar) {
			$navbar->left(view('admin::partials.topbar-left'));
			$navbar->right(view('admin::partials.topbar-right'));

			$navbar->right(Link::make('设置', 'setting/system', 'fa-cog'));
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
					'model' => Staff::class,
				],
			],
		];
		config(Arr::dot($auth, 'auth.'));
	}

	private function configHorizon()
	{
		$horizon = [
			'path' => config('admin.route.prefix') . '/horizon',
			'middleware' => config('admin.route.middleware'),
		];
		config(Arr::dot($horizon, 'horizon.'));
	}
}
