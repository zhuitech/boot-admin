<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Tools;

use Arr;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Str;

class Link extends AbstractTool
{
	protected $link;
	protected $type;
	protected $title;

    /**
     * Create a new CreateButton instance.
     *
     * @param Grid $grid
     */
    public function __construct($link, $title, $type = 'success')
    {
		$this->link = $link;
		$this->type = $type;
		$this->title = $title;
    }

    /**
     * Render CreateButton.
     *
     * @return string
     */
    public function render()
    {
        return <<<EOT

<div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
    <a href="{$this->link}" class="btn btn-sm btn-success" title="{$this->title}">{$this->title}</a>
</div>

EOT;
    }
}
