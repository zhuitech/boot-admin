<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 时间戳
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Timestamp extends AbstractDisplayer
{
    /**
     * Display method.
     *
     * @param string $format
     * @return mixed
     */
    public function display($format = 'Y-m-d H:i:s')
    {
        return self::render($this->value, $format);
    }
    
    public static function render($value, $format = 'Y-m-d H:i:s')
    {
        return date($format, $value);
    }
}