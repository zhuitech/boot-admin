<?php

namespace ZhuiTech\BootAdmin\Admin\Show;

use Encore\Admin\Show\AbstractField;

class Timestamp extends AbstractField
{
    public function render($format = 'Y-m-d H:i:s')
    {
        return \ZhuiTech\BootAdmin\Admin\Grid\Displayers\Timestamp::render($this->value, $format);
    }
}