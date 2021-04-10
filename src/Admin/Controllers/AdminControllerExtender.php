<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

interface AdminControllerExtender
{
	public function grid(Grid $grid);

	public function gridTools(Grid\Tools $tools);

	public function gridActions(Grid\Displayers\Actions $actions);

	public function gridBatchActions(Grid\Tools\BatchActions $batch);

	public function gridFilter(Grid\Filter $filter);

	public function form(Form $form, $section = null);

	public function formTools(Form\Tools $tools);

	public function formFooter(Form\Footer $footer);

	public function show(Show $show);

	public function showTools(Show\Tools $tools);
}