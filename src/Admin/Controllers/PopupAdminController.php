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

	protected function configGrid(Grid $grid, $mode = 'editable', $options = [])
	{
		$grid->setActionClass(DropdownActions::class);

		switch ($mode) {
			case 'editable':
				$grid->actions(function (Grid\Displayers\DropdownActions $actions) use ($options) {
					$actions->disableView()->disableEdit();
					$actions->add(new PopupEdit($options['edit'] ?? null));

					if (isset($options['actionsCallback'])) {
						$options['actionsCallback']($actions);
					}
				});

				$grid->disableCreateButton()
					->tools(function (Grid\Tools $tools) use ($grid, $options) {
						$tools->append(new PopupCreate($grid, $options['create'] ?? null));

						if (isset($options['toolsCallback'])) {
							$options['toolsCallback']($tools);
						}
					});
				break;

			case 'readonly':
				$grid->actions(function (Grid\Displayers\DropdownActions $actions) use ($options) {
					$actions->disableView()->disableEdit()->disableDelete();
				});

				$grid->disableCreateButton();
				break;
		}

		$grid->disableBatchActions()->disableColumnSelector()->disableExport()->disableFilter()->disablePagination();
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