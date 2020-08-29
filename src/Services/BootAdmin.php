<?php

namespace ZhuiTech\BootAdmin\Services;

use Encore\Admin\Admin;

class BootAdmin
{
	/***
	 * 获取自定义扩展
	 */
	public static function extensions()
	{
		$extensions = [];
		$modules = explode(',', config('boot-laravel.load_modules'));
		if (!empty($modules)) {
			foreach ($modules as $name) {
				$class = Admin::$extensions[$name] ?? null;
				if ($class) {
					$extensions[$name] = new $class;
				}
			}
		}

		return $extensions;
	}
}