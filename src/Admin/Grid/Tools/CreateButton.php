<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Tools;

use Arr;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Str;

class CreateButton extends AbstractTool
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Create a new CreateButton instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
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

        return <<<EOT

<div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
    <a href="{$url}" class="btn btn-sm btn-success" title="{$new}">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{$new}</span>
    </a>
</div>

EOT;
    }
}
