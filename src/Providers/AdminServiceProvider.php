<?php

namespace ZhuiTech\BootAdmin\Providers;

use Encore\Admin\Form;
use Encore\Admin\Grid\Column;
use Encore\Admin\Show;
use ZhuiTech\BootAdmin\Admin\Extensions\Actions\ClearCache;
use ZhuiTech\BootAdmin\Admin\Form\Fields\CKEditor;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Admin;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Admin as AdminUser;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Call;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Edit;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\FileLink;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Format;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Image;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Json;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\LargeFile;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\RemoteUser;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Route;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Thumbnail;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Timestamp;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Yuan;
use ZhuiTech\BootAdmin\Admin\Menu\AdminMenuFacade;
use ZhuiTech\BootAdmin\Console\AdminCommand;
use ZhuiTech\BootAdmin\Console\ServiceCommand;
use ZhuiTech\BootLaravel\Providers\AbstractServiceProvider;

class AdminServiceProvider extends AbstractServiceProvider
{
	protected $commands = [
		AdminCommand::class,
		ServiceCommand::class,
	];

	protected $facades = [
		'AdminMenu' => AdminMenuFacade::class
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
		Column::extend('format', Format::class);
		Column::extend('filelink', FileLink::class);

		Form::extend('editor', CKEditor::class);

		Show::extend('yuan', \ZhuiTech\BootAdmin\Admin\Show\Yuan::class);
		Show::extend('array', \ZhuiTech\BootAdmin\Admin\Show\JsonArray::class);

		\Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
			$navbar->right(new ClearCache());
		});

		parent::register();
	}
}
