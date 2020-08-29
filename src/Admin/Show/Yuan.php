<?php

namespace ZhuiTech\BootAdmin\Admin\Show;

use Encore\Admin\Show\AbstractField;

class Yuan extends AbstractField
{
	public function render($arg = '')
	{
		return \ZhuiTech\BootAdmin\Admin\Grid\Displayers\Yuan::render($this->value);
	}
}