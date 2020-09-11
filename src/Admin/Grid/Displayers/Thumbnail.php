<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Croppa;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 显示缩略图
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Thumbnail extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @param int $width
	 * @param int $height
	 * @return mixed
	 */
	public function display($width = 200, $height = 200)
	{
		return static::render($this->value, $width, $height);
	}

	public static function render($value, $width = 200, $height = 200)
	{
		if ($value) {
			$url = storage_url($value);
			$src = Croppa::url($url, $width, $height);
			return <<<EOT
        <a href="{$url}" target="_blank"><img src="{$src}" class="img img-thumbnail" /></a>
EOT;
		} else {
			$src = url('vendor/boot-admin/img/no-image.png');

			$style = '';
			if ($width)
				$style .= "max-width:{$width}px;";
			if ($height)
				$style .= "max-height:{$height}px;";

			return <<<EOT
        <img src="{$src}" class="img img-thumbnail" style="{$style}" />
EOT;
		}
	}
}