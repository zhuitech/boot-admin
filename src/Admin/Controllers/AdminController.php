<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\DropdownActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AdminController extends \Encore\Admin\Controllers\AdminController
{
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
        parent::index($content);
        return $this->configContent($content, $this->title(), $this->description['index'] ?? trans('admin.list'));
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
        parent::show($id, $content);
        return $this->configContent($content, $this->title(), $this->description['show'] ?? trans('admin.show'), __('admin.show'));
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
        parent::edit($id, $content);
        return $this->configContent($content, $this->title(), $this->description['edit'] ?? trans('admin.edit'), __('admin.edit'));
    }

    /**
     * 创建
     * 
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        parent::create($content);
        return $this->configContent($content, $this->title(), $this->description['create'] ?? trans('admin.create'), __('admin.create'));
    }

    /**
     * 设置内容
     * 
     * @param Content $content
     * @return Content
     */
    protected function configContent(Content $content, $title = null, $description = null, $action = null)
    {
        $breadcrumbs = [];
        
        // 一级页面
        $top = \BackendMenu::getCurrentTopMenu();
        if (!empty($top)) {
            $breadcrumbs[] = ['text' => $top['title'], 'url' => $top['uri']];
        }
        
        // 二级页面
        $paths = explode('/', Str::replaceFirst(admin_base_path(), '', request()->getPathInfo()));
        array_pop($paths);
        $breadcrumbs[] = ['text' => $title, 'url' => implode('/', $paths)];

        // 三级页面
        if (!empty($action)) {
            $breadcrumbs[] = ['text' => $action];
        }
        
        return $content->title($title)->description($description)->breadcrumb(... $breadcrumbs);
    }

    /**
     * 设置表格
     *
     * @param Grid $grid
     * @return Grid
     */
    protected function configGrid(Grid $grid)
    {
        $grid->model()->orderBy('created_at', 'desc');
        $grid->disableExport()->disableRowSelector()->setActionClass(DropdownActions::class);

        $grid->actions(function (Grid\Displayers\Actions $actions) {
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

    /**
     * 设置详情
     * 
     * @param Show $show
     * @return Show
     */
    protected function configShow(Show $show)
    {
        $show->panel()->tools(function (Show\Tools $tools) {
            $tools->disableEdit();
            $tools->disableList();
            $tools->disableDelete();
        });
        return $show;
    }

    /**
     * @param Form $form
     * @param $selectName
     * @param $formName
     * @param $formOptions
     */
    protected function selectForm(Form $form, $selectField, $formField, $formOptions)
    {
        foreach ($formOptions as $key => $config) {
            $html = <<<HTML
<div class="field-$formField field-$formField-$key" style="display: none;">
HTML;
            $form->html($html)->plain();
            $form->embeds("$formField.$key", $config['name'], function (Form\EmbeddedForm $form) use ($config) {
                foreach ($config['options'] as $field => $value) {
                    if (is_array($value)) {
                        $value += ['title' => '', 'help' => '', 'type' => 'text'];
                        switch ($value['type']) {
                            case 'textarea':
                                $form->textarea($field, $value['title'])->placeholder($value['help']);
                                break;
                            case 'select':
                                $form->select($field, $value['title'])->placeholder($value['help'])->options($value['options']);
                                break;
                            default:
                                $form->text($field, $value['title'])->placeholder($value['help']);
                                break;
                        }
                    }
                    else {
                        $form->text($field, $value)->placeholder($value);
                    }
                }
            });
            $form->html('</div>')->plain();
        }

        $form->saving(function (Form $form) use ($selectField, $formField) {
            $data = request()->all();
            $form->model()->$formField = [
                $data[$selectField] => $data["{$formField}_{$data[$selectField]}"]
            ];
        });

        Admin::script(<<<SCRIPT
$(function () {
    var {$selectField}Changed = function() {
        var agent = $('select[name="$selectField"]').val();
        $('.field-$formField').hide();
        $('.field-$formField-' + agent).show();
    };
    {$selectField}Changed();
    $('select[name="$selectField"]').change(function(){
        {$selectField}Changed();
    });
});
SCRIPT
        );
    }
}