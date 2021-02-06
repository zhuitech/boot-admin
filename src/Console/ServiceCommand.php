<?php

namespace ZhuiTech\BootAdmin\Console;

use Illuminate\Console\Command;
use ZhuiTech\BootAdmin\Admin\Extension;
use ZhuiTech\BootLaravel\Helpers\RestClient;

class ServiceCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'zhuitech:service';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '链接服务模块';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->menus();
	}

	private function menus()
	{
		// 菜单
		$menus = RestClient::server('service')->get('api/svc/system/menus');
		foreach ($menus['data'] ?? [] as $root) {
			Extension::createMenuTree($root);
		}

		// 权限
		$permissions = RestClient::server('service')->get('api/svc/system/permissions');
		foreach ($permissions['data'] ?? [] as $permission) {
			Extension::createPermission($permission['name'], $permission['slug'], $permission['path'], $permission['method']);
		}
	}
}