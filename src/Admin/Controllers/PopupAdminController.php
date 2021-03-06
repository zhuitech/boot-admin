<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\DropdownActions;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Str;
use ZhuiTech\BootAdmin\Admin\Grid\Actions\PopupEdit;
use ZhuiTech\BootAdmin\Admin\Grid\Tools\PopupCreate;

/**
 * 对话框式
 * Class PopupAdminController
 * @package ZhuiTech\BootAdmin\Admin\Controllers
 */
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

	public function index(Content $content)
	{
		$grid = $this->grid()->render();
		return $this->dialog($grid);
	}

	protected function dialog($form)
	{
		$title = $this->title();

		// 移除pjax
		$form = Str::replaceFirst(' pjax-container>', ' >', $form);
		return view('admin::widgets.modal-form', compact('title', 'form'));
	}
}