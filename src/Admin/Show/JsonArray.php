<?php

namespace ZhuiTech\BootAdmin\Admin\Show;

use Encore\Admin\Show\AbstractField;

class JsonArray extends AbstractField
{
    public $escape = false;
    public $border = false;

    public function render($arg = '')
    {
        return \ZhuiTech\BootAdmin\Admin\Grid\Displayers\Json::render($this->value);
    }
}