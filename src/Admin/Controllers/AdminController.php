<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\DropdownActions;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Arr;
use ZhuiTech\BootAdmin\Admin\Grid\Tools\PopupCreate;

class AdminController extends \Encore\Admin\Controllers\AdminController
{
    protected $active;

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
     * 获取资源ID
     *
     * @return mixed
     */
    protected function getKey()
    {
        $parameters = request()->route()->parameters;
        return Arr::last($parameters);
    }

    /**
     * 更新
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $id = $this->getKey();
        return parent::update($id);
    }

    /**
     * 删除
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = $this->getKey();
        return parent::destroy($id);
    }

    /**
     * 列表
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $breadcrumbs = $this->breadcrumb();
        $breadcrumbs[] = ['text' => $this->title(), 'left-menu-active' => $this->active ?? $this->title()];

        return $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->breadcrumb(... $breadcrumbs)
            ->body($this->grid());
    }

    /**
     * 详情
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        $id = $this->getKey();
        return parent::show($id, $content);
    }

    /**
     * 编辑
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        $id = $this->getKey();
        return parent::edit($id, $content);
    }

    /**
     * 设置表格
     *
     * @param Grid $grid
     * @return Grid
     */
    protected function configGrid(Grid $grid)
    {
        $grid->disableExport()->disableRowSelector()->setActionClass(DropdownActions::class);

        $grid->actions(function (Grid\Displayers\DropdownActions $actions) {
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * 设置表单
     *
     * @param Form $form
     * @return Form
     */
    protected function configForm(Form $form)
    {
        $form->display('created_at', '创建时间');
        $form->display('updated_at', '更新时间');

        $this->configFormTools($form->builder()->getTools());
        $this->configFormFooter($form->builder()->getFooter());

        return $form;
    }

    /**
     * @param Form\Footer $footer
     * @return Form\Footer
     */
    protected function configFormFooter(Form\Footer $footer)
    {
        return $footer->disableViewCheck()->disableEditingCheck()->disableCreatingCheck();
    }

    /**
     * @param Form\Tools $tools
     * @return Form\Tools
     */
    protected function configFormTools(Form\Tools $tools)
    {
        return $tools->disableView();
    }
}