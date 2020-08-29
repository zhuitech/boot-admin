<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Tools;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;

class PopupCreate extends AbstractTool
{
	/**
	 * @var Grid
	 */
	protected $grid;

	/**
	 * @var null
	 */
	private $url;

	/**
	 * Create a new CreateButton instance.
	 *
	 * @param Grid $grid
	 * @param null $url
	 */
	public function __construct(Grid $grid, $url = NULL)
	{
		$this->grid = $grid;
		$this->url = $url;
	}

	/**
	 * Render CreateButton.
	 *
	 * @return string
	 */
	public function render()
	{
		$new = trans('admin.new');
		$url = $this->url ?? $this->grid->getCreateUrl();

		return <<<EOT
<div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
    <a href="javascript:" class="btn btn-sm btn-success" data-url="{$url}" title="{$new}" data-toggle="modal" data-target="#modal" data-backdrop="static" data-keyboard="false">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{$new}</span>
    </a>
</div>

EOT;
	}
}