<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Tools;

use Encore\Admin\Grid\Tools\AbstractTool;

class Link extends AbstractTool
{
	protected $link;
	protected $type;
	protected $title;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * Create a new CreateButton instance.
	 *
	 * @param $link
	 * @param $title
	 * @param string $type
	 * @param string $icon
	 */
	public function __construct($link, $title, $type = 'success', $icon = 'plus')
	{
		$this->link = $link;
		$this->type = $type;
		$this->title = $title;
		$this->icon = $icon;
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
    <a href="{$this->link}" class="btn btn-sm btn-success" title="{$this->title}"><i class="fa fa-{$this->icon}"></i>&nbsp; {$this->title}</a>
</div>
EOT;
	}
}
