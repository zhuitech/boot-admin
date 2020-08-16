<?php


namespace ZhuiTech\BootAdmin\Console;


use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use ZhuiTech\BootAdmin\Admin\Extension;
use ZhuiTech\BootLaravel\Helpers\RestClient;

class ServiceCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'zhuitech:svc-link';

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
		$output = new ConsoleOutput();

		// 菜单
		$menus = RestClient::server('service')->get('api/svc/system/menus');
		foreach ($menus['data'] ?? [] as $root) {
			Extension::createMenuTree($root);
		}

		// 权限
		$permissions = RestClient::server('service')->get('api/svc/system/permissions');
		foreach ($permissions['data'] ?? [] as $permission) {
			$permissionModel = config('admin.database.permissions_model');
			$permissionModel::updateOrCreate([
				'slug' => $permission['slug'],
			], [
				'name' => $permission['name'],
				'http_path' => '/' . trim($permission['http_path'], '/'),
				'http_method' => $permission['http_method'],
			]);
			$output->writeln("<info>权限[{$permission['name']}]更新成功</info>");
		}
	}
}