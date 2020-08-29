<?php

namespace ZhuiTech\BootAdmin\Admin\Show;

use Encore\Admin\Show\AbstractField;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Json;

class JsonArray extends AbstractField
{
	public $escape = false;
	public $border = false;

	public function render($arg = '')
	{
		return Json::render($this->value);
	}
}