<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use ZhuiTech\BootLaravel\Helpers\FileHelper;

class UserController extends \Encore\Admin\Controllers\UserController
{
    public function form()
    {
        $form = parent::form();
        
        $form->email('email', '邮箱');
        $form->mobile('mobile', '手机')->rules('required');
        $form->builder()->field('avatar')->dir(FileHelper::dir('avatar'));
        
        return $form;
    }
}