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
class Admin extends AbstractDisplayer
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
        $admin = Administrator::find($value);
        return $admin->name ?? $admin->username ?? '';
    }
}