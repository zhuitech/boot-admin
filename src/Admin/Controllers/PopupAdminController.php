<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
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

    protected function configGrid(Grid $grid)
    {
        parent::configGrid($grid)->disableCreateButton();

        $grid->tools(function (Grid\Tools $tools) use ($grid) {
            $tools->append(new PopupCreate($grid));
        });

        $grid->actions(function (Grid\Displayers\DropdownActions $actions) {
            $actions->disableView()->disableEdit();
            $actions->add(new PopupEdit());
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
        return view('admin::widgets.form-dialog', compact('title', 'form'));
    }
}