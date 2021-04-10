<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 管理员
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Route extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @param string $route
	 * @param array $map
	 * @param array $query
	 * @return mixed
	 */
	public function display($route = '', $map = [], $query = [])
	{
		foreach ($map as $k => $v) {
			$query[$k] = $this->row->getAttribute($v);
		}

		$link = route($route, $query);
		return "<a href='{$link}'>{$this->value}</a>";
	}

	public static function render($value, $route, $query)
	{
		$link = route($route, $query);
		return "<a href='{$link}'>{$value}</a>";
	}
}