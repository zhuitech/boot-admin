<?php

namespace ZhuiTech\BootAdmin\Admin;

use DB;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class Extension extends \Encore\Admin\Extension
{
	public $records = [
		/*'table' => [
			'fields' => [],
			'records' => [],
		],*/
	];

	public $settings = [];

	public $permissions = [];

	/**
	 * 导入模块
	 * @throws Throwable
	 */
	public static function import()
	{
		$extension = static::getInstance();

		DB::transaction(function () use ($extension) {
			$output = new ConsoleOutput();

			// 菜单
			$menus = $extension->menu();
			if ($menus) {
				if (isset($menus['title'])) {
					$menus = [$menus];
				}

				foreach ($menus as $menu) {
					static::createMenuTree($menu);
				}
			}

			// 权限
			if ($extension->permissions) {
				foreach ($extension->permissions as $permission) {
					static::createPermission($permission['name'], $permission['slug'], $permission['path']);
				}
			}

			// 数据
			if ($extension->records) {
				foreach ($extension->records as $table => $data) {
					static::insertRecords($table, $data['fields'], $data['records']);
				}
			}

			// 配置
			if ($extension->settings) {
				$i = 0;
				foreach ($extension->settings as $key => $value) {
					if (empty(settings($key))) {
						settings([$key => $value]);
						$i++;
					}
				}
				if ($i > 0) {
					$output->writeln("<info>成功写入[{$i}]条配置数据</info>");
				}
			}
		});
	}

	/**
	 * 创建菜单树
	 * @param $root
	 */
	public static function createMenuTree($root)
	{
		$menuModel = config('admin.database.menu_model');
		$output = new ConsoleOutput();

		// 根菜单
		if (empty($rootMenu = $menuModel::where(['title' => $root['title'], 'parent_id' => 0])->first())) {
			$order = $menuModel::where('parent_id', 0)->max('order') + 1;
			$rootMenu = $menuModel::create([
				'parent_id' => 0,
				'order' => $order,
				'title' => $root['title'] ?? '',
				'icon' => $root['icon'] ?? 'fa-circle-o',
				'uri' => $root['uri'] ?? '',
			]);
			$rootMenu->blank = $root['blank'] ?? 0;
			$rootMenu->save();
		}

		foreach ($root['children'] ?? [] as $parent) {
			// 父菜单
			if (empty($parentMenu = $menuModel::where(['title' => $parent['title'] ?? '', 'parent_id' => $rootMenu->id])->first())) {
				$order = $menuModel::where('parent_id', $rootMenu->id)->max('order') + 1;
				$parentMenu = $menuModel::create([
					'parent_id' => $rootMenu->id,
					'order' => $order,
					'title' => $parent['title'] ?? '',
					'icon' => $parent['icon'] ?? 'fa-circle-o',
					'uri' => $parent['uri'] ?? '',
				]);
				$parentMenu->blank = $parent['blank'] ?? 0;
				$parentMenu->save();

				$output->writeln("<info>菜单[{$parent['title']}]创建成功</info>");
			}

			// 子菜单
			foreach ($parent['children'] ?? [] as $child) {
				if (empty($childMenu = $menuModel::where(['title' => $child['title'] ?? '', 'parent_id' => $parentMenu->id])->first())) {
					$order = $menuModel::where('parent_id', $parentMenu->id)->max('order') + 1;
					$childMenu = $menuModel::create([
						'parent_id' => $parentMenu->id,
						'order' => $order,
						'title' => $child['title'] ?? '',
						'icon' => $child['icon'] ?? 'fa-circle-o',
						'uri' => $child['uri'] ?? '',
					]);
					$childMenu->blank = $child['blank'] ?? 0;
					$childMenu->save();

					$output->writeln("<info>菜单[{$child['title']}]创建成功</info>");
				}
			}
		}
	}

	/**
	 * 插入记录
	 * @param $table
	 * @param $fields
	 * @param $records
	 */
	public static function insertRecords($table, $fields, $records)
	{
		$output = new ConsoleOutput();
		$count = DB::table($table)->count();

		if ($count == 0) {
			$inserts = [];
			foreach ($records as $record) {
				$insert = [];
				for ($i = 0; $i < count($fields); $i++) {
					$insert[$fields[$i]] = $record[$i];
				}
				$inserts[] = $insert;
			}
			$count1 = DB::table($table)->insertOrIgnore($inserts);

			$output->writeln("<info>成功向表[{$table}]插入{$count1}条数据</info>");
		}
	}

	/**
	 * 创建权限
	 * @param $name
	 * @param $slug
	 * @param $path
	 * @param array $methods
	 */
	public static function createPermission($name, $slug, $path, $methods = [])
	{
		$output = new ConsoleOutput();
		$permissionModel = config('admin.database.permissions_model');
		$permissionModel::updateOrCreate(['slug' => $slug,], [
			'name' => $name,
			'http_path' => '/' . trim($path, '/'),
			'http_method' => $methods,
		]);
		$output->writeln("<info>权限[{$name}]更新成功</info>");
	}
}