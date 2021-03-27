<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BaseControllerExtender implements AdminControllerExtender
{

	public function grid(Grid $grid)
	{
		// TODO: Implement grid() method.
	}

	public function gridTools(Grid\Tools $tools)
	{
		// TODO: Implement gridTools() method.
	}

	public function gridActions(Grid\Displayers\Actions $actions)
	{
		// TODO: Implement gridActions() method.
	}

	public function gridBatchActions(Grid\Tools\BatchActions $batch)
	{
		// TODO: Implement gridBatchActions() method.
	}

	public function gridFilter(Grid\Filter $filter)
	{
		// TODO: Implement gridFilter() method.
	}

	public function form(Form $form, $section = null)
	{
		// TODO: Implement form() method.
	}

	public function formTools(Form\Tools $tools)
	{
		// TODO: Implement formTools() method.
	}

	public function formFooter(Form\Footer $footer)
	{
		// TODO: Implement formFooter() method.
	}

	public function show(Show $show)
	{
		// TODO: Implement show() method.
	}

	public function showTools(Show\Tools $tools)
	{
		// TODO: Implement showTools() method.
	}
}