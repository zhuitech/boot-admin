<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Grid;
use ZhuiTech\BootLaravel\Helpers\FileHelper;

class UserController extends \Encore\Admin\Controllers\UserController
{
    public function form()
    {
        $form = parent::form();
        
        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->mobile('mobile', '手机')
            ->creationRules(['required', "unique:{$connection}.{$userTable},mobile"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},mobile,{{id}}"]);
        
        $form->email('email', '邮箱')
            ->creationRules(['required', "unique:{$connection}.{$userTable},email"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},email,{{id}}"]);
        
        $form->builder()->field('avatar')->dir(FileHelper::dir('avatar'));
        
        return $form;
    }

    protected function grid()
    {
        $userModel = config('admin.database.users_model');

        $grid = new Grid(new $userModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('username', trans('admin.username'));
        $grid->column('name', trans('admin.name'));
        $grid->column('email', '邮箱');
        $grid->column('mobile', '手机');
        $grid->column('roles', trans('admin.roles'))->pluck('name')->label();
        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->getKey() == 1) {
                $actions->disableDelete();
            }
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

	    // 筛选
	    $grid->filter(function (Grid\Filter $filter) {
		    $filter->disableIdFilter();
		    $filter->like('username', '用户名');
		    $filter->like('name', '名称');
		    $filter->like('email', '邮箱');
		    $filter->like('mobile', '手机');
	    });

        return $grid;
    }
}