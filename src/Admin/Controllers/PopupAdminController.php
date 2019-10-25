<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\DropdownActions;
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ZhuiTech\BootAdmin\Admin\Grid\Actions\PopupEdit;
use ZhuiTech\BootAdmin\Admin\Grid\Tools\PopupCreate;

class PopupAdminController extends AdminController
{
    public function create(Content $content)
    {
        $form = $this->form()->render();
        return $this->dialog($form);
    }

    public function edit($id, Content $content)
    {
        $id = $this->getKey();
        $form = $this->form()->edit($id)->render();
        return $this->dialog($form);
    }

    protected function configGrid(Grid $grid, $mode = 'editable')
    {
        /* @var Model $model */
        $model = $grid->model();
        $model->orderBy('created_at', 'desc');

        $grid->setActionClass(DropdownActions::class)
            ->actions(function (Grid\Displayers\DropdownActions $actions) {
                $actions->disableView()->disableEdit();
                $actions->add(new PopupEdit());
            })
            ->batchActions(function (Grid\Tools\BatchActions $batch) {
                $batch->disableDelete();
            })->filter(function(Grid\Filter $filter){
                $filter->disableIdFilter();
            });

        $grid->disableCreateButton()
            ->tools(function (Grid\Tools $tools) use ($grid) {
            $tools->append(new PopupCreate($grid));
        });

        return $grid;
    }

    protected function configFormFooter(Form\Footer $footer)
    {
        return parent::configFormFooter($footer)->disableReset()->disableSubmit();
    }

    protected function configFormTools(Form\Tools $tools)
    {
        return parent::configFormTools($tools)->disableDelete()->disableList();
    }

    protected function dialog($form)
    {
        $title = $this->title();

        // 移除pjax
        $form = Str::replaceFirst(' pjax-container>', ' >', $form);
        return view('admin::widgets.modal-form', compact('title', 'form'));
    }
}