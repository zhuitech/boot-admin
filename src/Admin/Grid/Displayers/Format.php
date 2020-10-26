<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 格式化
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Format extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @param array $pipes
	 * @return mixed
	 */
	public function display($pipes = [])
	{
		return pipe_format($this->value, $pipes);
	}
}