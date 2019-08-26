<?php

namespace ZhuiTech\BootAdmin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class AdminController extends \Encore\Admin\Controllers\AdminController
{
    /**
     * 列表页
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $breadcrumbs = $this->breadcrumb();
        $breadcrumbs[] = ['text' => $this->title(), 'left-menu-active' => $this->title()];

        return $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->breadcrumb(... $breadcrumbs)
            ->body($this->grid());
    }

    /**
     * 面包屑
     *
     * @return array
     */
    protected function breadcrumb()
    {
        return [];
    }

    /**
     * 设置表格
     *
     * @param Grid $grid
     */
    protected function configGrid(Grid $grid)
    {
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });
    }

    /**
     * 设置表单
     *
     * @param Form $form
     */
    protected function configForm(Form $form)
    {
        $form->display('created_at', '创建时间');
        $form->display('updated_at', '更新时间');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });

        $form->footer(function (Form\Footer $footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
    }
}