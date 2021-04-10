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
	 * @param int $limit
	 * @return mixed
	 */
	public function display($width = 200, $height = 200, $limit = 3)
	{
		if (empty($this->value)) {
			return static::render($this->value, $width, $height);
		}

		$values = $this->value;
		if (!is_array($values)) {
			$values = [$values];
		}

		$html = '';
		foreach (array_slice($values, 0, $limit) as $value) {
			$html .= static::render($value, $width, $height);
		}
		return $html;
	}

	public static function render($value, $width = 200, $height = 200)
	{
		if ($value) {
			$url = storage_url($value);
			$src = Croppa::url(str_replace('storage', 'storage/crops', $url), $width, $height);
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