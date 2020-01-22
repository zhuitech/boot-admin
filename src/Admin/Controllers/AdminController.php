<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\DropdownActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ZhuiTech\BootAdmin\Admin\Form\ModelForm;
use ZhuiTech\BootAdmin\Admin\Form\SwitchPanel;

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
     * @param string $mode
     * @return Grid
     */
    protected function configGrid(Grid $grid, $mode = 'editable')
    {
        /* @var Model $model */
        $model = $grid->model();

        switch ($mode) {
            case 'editable':
                $model->orderBy('created_at', 'desc');
                $grid->setActionClass(DropdownActions::class)
                    ->actions(function (Grid\Displayers\Actions $actions) {
                        $actions->disableView();
                    })
                    ->batchActions(function (Grid\Tools\BatchActions $batch) {
                        $batch->disableDelete();
                    })->filter(function(Grid\Filter $filter){
                        $filter->disableIdFilter();
                    });
                break;

            case 'readonly':
                $model->orderBy('created_at', 'desc');
                $grid->setActionClass(DropdownActions::class)->disableCreateButton()
                    ->actions(function (Grid\Displayers\Actions $actions) {
                        $actions->disableDelete()->disableEdit();
                    })
                    ->batchActions(function (Grid\Tools\BatchActions $batch) {
                        $batch->disableDelete();
                    })->filter(function(Grid\Filter $filter){
                        $filter->disableIdFilter();
                    });
                break;

            case 'removable':
                $model->orderBy('created_at', 'desc');
                $grid->setActionClass(DropdownActions::class)->disableCreateButton()
                    ->actions(function (Grid\Displayers\Actions $actions) {
                        $actions->disableEdit()->disableView();
                    })
                    ->batchActions(function (Grid\Tools\BatchActions $batch) {
                        $batch->disableDelete();
                    })->filter(function(Grid\Filter $filter){
                        $filter->disableIdFilter();
                    });
                break;
        }
        
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
     * @param string $mode
     * @return Show
     */
    protected function configShow(Show $show, $mode = 'box')
    {
        switch ($mode) {
            case 'box':
                $show->panel()->title('');
                $show->panel()->tools(function (Show\Tools $tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
                break;

            case 'readonly':
                $show->panel()->tools(function (Show\Tools $tools) {
                    $tools->disableEdit();
                    $tools->disableDelete();
                });
                $show->field('created_at', '创建时间');
                $show->field('updated_at', '更新时间');
                break;
        }

        return $show;
    }

    /**
     * @param ModelForm $form
     * @param $select_name
     * @param $option_name
     * @param $options
     */
    protected function selectForm(ModelForm $form, $select_name, $option_name, $options)
    {
        foreach ($options as $key => $config) {
            SwitchPanel::create($form, function (ModelForm $form) use ($option_name, $key, $config) {
                // 创建子表单
                $option_form = "{$option_name}_{$key}";
                $form->embeds($option_form, $config['name'], function (Form\EmbeddedForm $form) use ($config) {
                    foreach ($config['options'] as $field => $value) {
                        if (!is_array($value)) {
                            $value = ['type' => 'text', 'title' => $value];
                        }

                        $value += ['title' => '', 'help' => '', 'type' => 'text'];
                        switch ($value['type']) {
                            case 'textarea':
                                $form_item = $form->textarea($field, $value['title']);
                                break;
                            case 'select':
                                $form_item = $form->select($field, $value['title'])->options($value['options']);
                                break;
                            case 'file':
                                $value += ['disk' => 'local', 'dir' => 'local'];
                                $form_item = $form->file($field, $value['title'])->disk($value['disk'])->dir($value['dir']);
                                break;
                            default:
                                $form_item = $form->text($field, $value['title']);
                                break;
                        }

                        if (!empty($form_item) && !empty($value['help'])) {
                            $form_item->help($value['help']);
                        }
                    }
                });
            }, $select_name, $key);
        }

        $form->editing(function (ModelForm $form) use ($select_name, $option_name) {
            $model = $form->model();
            // 生成子表单数据
            $option_form = "{$option_name}_{$model->$select_name}";
            $model->$option_form = $model->$option_name;
        });

        $form->prepared(function (ModelForm $form) use ($select_name, $option_name, $options) {
            $prepared = $form->preparedValues;

            // 复制到实际字段
            $option_form = "{$option_name}_{$prepared[$select_name]}";
            $prepared[$option_name] = $prepared[$option_form];

            // 删除子表单数据
            foreach ($options as $key => $config) {
                $option_form = "{$option_name}_{$key}";
                unset($prepared[$option_form]);
            }

            $form->preparedValues = $prepared;
        });

        SwitchPanel::script($select_name, 'select');
    }
}