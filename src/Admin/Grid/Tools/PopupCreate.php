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

        return <<<EOT
<div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
    <a href="javascript:" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal" data-backdrop="static" data-keyboard="false"
        data-url="{$this->grid->getCreateUrl()}" title="{$new}">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{$new}</span>
    </a>
</div>

EOT;
    }
}
