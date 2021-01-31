<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;
use Exception;
use Illuminate\Support\Facades\File;

/**
 * 大文件
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class LargeFile extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function display()
	{
		return static::render($this->value);
	}

	public static function render($value)
	{
		if ($value) {
			return FileLink::render(large_path($value));
		}
	}
}