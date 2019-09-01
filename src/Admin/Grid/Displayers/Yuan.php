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
        return yuan($this->value);
    }
}