<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Tools;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateButton extends AbstractTool
{
	/**
	 * @var Grid
	 */
	protected $grid;

	/**
	 * @var bool
	 */
	private $pjax;

	/**
	 * Create a new CreateButton instance.
	 *
	 * @param Grid $grid
	 * @param bool $pjax
	 */
	public function __construct(Grid $grid, $pjax = true)
	{
		$this->grid = $grid;
		$this->pjax = $pjax;
	}

	/**
	 * Render CreateButton.
	 *
	 * @return string
	 */
	public function render()
	{
		$new = trans('admin.new');

		$url = $this->grid->getCreateUrl();
		$query = Arr::except(request()->query(), ['_pjax']);
		$url .= (Str::contains($url, '?') ? '' : '?') . http_build_query($query);
		$pjax = $this->pjax ? '' : 'no-pjax';

		return <<<EOT

		<div class="btn-group" style="margin-right: 5px">
		    <a href="{$url}" class="btn btn-sm btn-success" title="{$new}" {$pjax}>
		        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{$new}</span>
		    </a>
		</div>

EOT;
	}
}
