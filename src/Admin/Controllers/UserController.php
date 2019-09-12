<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

class UserController extends \Encore\Admin\Controllers\UserController
{
    public function form()
    {
        $form = parent::form();
        
        $form->email('email', '邮箱')->rules('required');
        $form->mobile('mobile', '手机')->rules('required');
        
        return $form;
    }
}