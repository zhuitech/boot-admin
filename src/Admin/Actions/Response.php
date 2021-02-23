<?php

namespace ZhuiTech\BootAdmin\Admin\Actions;

class Response extends \Encore\Admin\Actions\Response
{
	public function next($next)
	{
		$this->then = ['action' => 'next', 'value' => $next];

		return $this;
	}
}