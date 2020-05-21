<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 显示缩略图
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Thumbnail extends AbstractDisplayer
{
    /**
     * Display method.
     *
     * @param int $width
     * @param int $height
     * @return mixed
     */
    public function display($width = 200, $height = 200)
    {
        $src = \Croppa::url(storage_url($this->value), $width, $height);
        return "<img src='$src' class='img img-thumbnail' />";
    }
}