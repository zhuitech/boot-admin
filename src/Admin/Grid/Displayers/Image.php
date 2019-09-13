<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 图片
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Image extends \Encore\Admin\Grid\Displayers\Image
{
    public function display($server = '', $width = 200, $height = 200)
    {
        $result = parent::display($server, $width, $height);

        if (empty($result)) {
            $src = url('vendor/boot-admin/img/no-image.png');
            return "<img src='$src' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail' />";
        } 
        
        return $result;
    }
}