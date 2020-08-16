<?php

namespace ZhuiTech\BootAdmin\Admin\Extensions\Nav;

use Illuminate\Contracts\Support\Renderable;

class Shortcut implements Renderable
{
	protected $links;

	protected $icon = 'fa-plus';

	protected $title;

	public function __construct(array $links = [], $icon = 'fa-plus')
	{
		$this->links = collect($links);
		$this->icon = $icon;
	}

	public static function make($links = [], $icon = '')
	{
		return new static($links, $icon);
	}

	public function title($title = '')
	{
		$this->title = $title;

		return $this;
	}

	public function icon($icon)
	{
		$this->icon = $icon;

		return $this;
	}

	public function render()
	{
		$links = $this->links->map(function ($target, $title) {

			$link = admin_url($target);

			return "<li><a href='$link'>{$title}</a></li>";
		})->implode("\r\n");

		return <<<HTML
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa {$this->icon}"></i>
        <span>{$this->title}</span>
    </a>
    <ul class="dropdown-menu">
        {$links}
    </ul>
</li>
HTML;
	}
}