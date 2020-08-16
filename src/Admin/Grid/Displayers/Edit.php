<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 链接
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Edit extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @return mixed
	 */
	public function display()
	{
		$url = url("{$this->getResource()}/{$this->getKey()}/edit");
		return <<<EOT
        <a href="$url" target="_blank">{$this->value}</a>
EOT;

	}
}