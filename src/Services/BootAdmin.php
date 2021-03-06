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
		$modules = config('boot-laravel.modules');
		$load_modules = array_filter(explode(',', config('boot-laravel.load_modules')));

		foreach ($modules as $name => $module) {
			if (in_array($name, $load_modules) || empty($load_modules)) {
				$class = Admin::$extensions[$name] ?? null;
				if ($class) {
					$extensions[$name] = new $class;
				}
			}
		}

		return $extensions;
	}
}