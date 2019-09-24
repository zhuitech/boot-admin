<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

class MenuController extends \Encore\Admin\Controllers\MenuController
{
    public function form()
    {
        $form = parent::form();
        $form->builder()->field('icon')->rules(function () {
            return '';
        });

        return $form;
    }
}