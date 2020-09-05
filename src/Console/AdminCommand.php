<?php

namespace ZhuiTech\BootAdmin\Console;

use Artisan;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Console\Command;
use ZhuiTech\BootAdmin\Admin\Extension;
use ZhuiTech\BootAdmin\Services\BootAdmin;

class AdminCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'zhuitech:admin';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '安装后台模块';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		// 初始化数据
		$this->menus();
		$this->permissions();
		$this->roles();
		$this->users();
		$this->import();
	}

	/**
	 * 导入权限
	 */
	private function permissions()
	{
		if (!Permission::where(['slug' => '*'])->first()) {
			Permission::insert([
				[
					'name' => '所有权限',
					'slug' => '*',
					'http_method' => '',
					'http_path' => '*',
				], [
					'name' => '基础权限',
					'slug' => 'admin.dashboard',
					'http_method' => '',
					'http_path' => '/
/auth/setting
/login
/logout
/_handle_action_
/svc/_handle_action_',
				], [
					'name' => '授权管理',
					'slug' => 'admin.auth',
					'http_method' => '',
					'http_path' => '/auth*',
				], [
					'name' => '系统日志',
					'slug' => 'admin.logs',
					'http_method' => '',
					'http_path' => '/logs*',
				],
			]);

			$this->line("<info>Permissions insert successfully.</info>");
		}
	}

	/**
	 * 导入角色
	 */
	private function roles()
	{
		if (!Role::where(['slug' => 'administrator'])->first()) {
			$role = Role::create([
				'name' => '系统管理员',
				'slug' => 'administrator',
			]);

			// 权限分配
			$role->permissions()->save(Permission::first());

			$this->line("<info>Role administrator insert successfully.</info>");
		}

		if (!Role::where(['slug' => 'manager'])->first()) {
			$role = Role::create([
				'name' => '普通管理员',
				'slug' => 'manager',
			]);

			// 权限分配
			$permissions = ['admin.dashboard', 'admin.logs'];
			foreach ($permissions as $permission) {
				$role->permissions()->save(Permission::where('slug', $permission)->first());
			}

			$this->line("<info>Role manager insert successfully.</info>");
		}
	}

	/**
	 * 导入用户
	 */
	private function users()
	{
		if (!Administrator::where(['username' => 'admin'])->first()) {
			$user = Administrator::create([
				'username' => 'admin',
				'password' => bcrypt('letmein2019'),
				'name' => '系统管理员'
			]);
			$user->mobile = '18017250227';
			$user->save();

			// add role to user.
			$user->roles()->save(Role::where(['slug' => 'administrator'])->first());

			$this->line("<info>User admin insert successfully.</info>");
		}

		if (!Administrator::where(['username' => 'manager'])->first()) {
			$user = Administrator::create([
				'username' => 'manager',
				'password' => bcrypt('letmein2019'),
				'name' => '普通管理员',
			]);

			// add role to user.
			$user->roles()->save(Role::where(['slug' => 'manager'])->first());

			$this->line("<info>User manager insert successfully.</info>");
		}
	}

	/**
	 * 导入菜单
	 */
	private function menus()
	{
		$menus = [
			'title' => '系统',
			'icon' => 'fa-cogs',
			'uri' => '/',
			'children' => [
				[
					'title' => '控制台',
					'icon' => 'fa-bar-chart',
					'uri' => '/',
				],
				[
					'title' => '安全管理',
					'icon' => 'fa-tasks',
					'children' => [
						[
							'title' => '管理员',
							'icon' => 'fa-users',
							'uri' => '/auth/users',
						],
						[
							'title' => '角色管理',
							'icon' => 'fa-user',
							'uri' => '/auth/roles',
						],
						[
							'title' => '权限管理',
							'icon' => 'fa-ban',
							'uri' => '/auth/permissions',
						],
						[
							'title' => '菜单管理',
							'icon' => 'fa-bars',
							'uri' => '/auth/menu',
						],
						[
							'title' => '操作日志',
							'icon' => 'fa-history',
							'uri' => '/auth/logs',
						],
					],
				],
				[
					'title' => '配置管理',
					'icon' => 'fa-gears',
					'children' => [
						[
							'title' => '系统设置',
							'icon' => 'fa-cog',
							'uri' => '/setting/system',
						],
					],
				],
				[
					'title' => '实用工具',
					'icon' => 'fa-wrench',
					'children' => [
						[
							'title' => '计划任务',
							'icon' => 'fa-clock-o',
							'uri' => '/scheduling',
						],
						[
							'title' => '队列任务',
							'icon' => 'fa-tasks',
							'uri' => '/helpers/horizon',
						],
						[
							'title' => '系统日志',
							'icon' => 'fa-history',
							'uri' => '/logs',
						],
						[
							'title' => '文件管理',
							'icon' => 'fa-file',
							'uri' => '/media',
						],
						[
							'title' => '命令行',
							'icon' => 'fa-terminal',
							'uri' => '/helpers/terminal/artisan',
						],
						[
							'title' => '路由',
							'icon' => 'fa-list-alt',
							'uri' => '/helpers/routes',
						],
						[
							'title' => '数据库',
							'icon' => 'fa-database',
							'uri' => '/helpers/terminal/database',
						],
						[
							'title' => 'Redis',
							'icon' => 'fa-codepen',
							'uri' => '/redis',
						],
						[
							'title' => '数据转换',
							'icon' => 'fa-retweet',
							'uri' => '/helpers/convert',
						],
					],
				],
			],
		];
		Extension::createMenuTree($menus);
	}

	/**
	 * 导入模块
	 */
	private function import()
	{
		$extensions = BootAdmin::extensions();
		foreach ($extensions as $key => $extension) {
			Artisan::call("admin:import $key");
		}
	}
}
