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
	private $options;

	/**
	 * Create a new CreateButton instance.
	 *
	 * @param Grid $grid
	 * @param array $options
	 */
	public function __construct(Grid $grid, $options = [])
	{
		$this->grid = $grid;
		$this->options = $options + [
				'title' => trans('admin.new'),
				'pjax' => true,
				'query' => []
			];
	}

	/**
	 * Render CreateButton.
	 *
	 * @return string
	 */
	public function render()
	{
		$url = $this->grid->getCreateUrl();
		$query = $this->options['query'] + Arr::except(request()->query(), ['_pjax']);
		$url .= (Str::contains($url, '?') ? '' : '?') . http_build_query($query);
		$pjax = $this->options['pjax'] ? '' : 'no-pjax';

		return <<<EOT
		<div class="btn-group" style="margin-right: 5px">
		    <a href="{$url}" class="btn btn-sm btn-success" title="{$this->options['title']}" {$pjax}>
		        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{$this->options['title']}</span>
		    </a>
		</div>
EOT;
	}
}
