<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 显示元
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Yuan extends AbstractDisplayer
{
    /**
     * Display method.
     *
     * @return mixed
     */
    public function display()
    {
        return self::render($this->value);
    }
    
    public static function render($value)
    {
        return '￥' . yuan($value); 
    }
}