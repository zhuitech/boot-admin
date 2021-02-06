<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;
use Illuminate\Support\Facades\File as FileFacade;

/**
 * 文件
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class FileLink extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @param null $icon
	 * @return mixed
	 */
	public function display($icon = null)
	{
		return static::render($this->value, $icon);
	}

	public static function render($value, $icon = null)
	{
		if ($value) {
			$src = storage_url($value);
			$name = basename($src);
			$attributes = '';

			if (!$icon) {
				$ext = FileFacade::extension($name);

				if (in_array($ext, ['mp4', 'm4v'])) {
					$icon = "video-camera";
				} else if (in_array($ext, ['mp3', 'wav'])) {
					$icon = 'music';
				} else {
					$icon = 'download';
					$attributes .= " download='{$name}'";
				}
			}

			return <<<HTML
<a href="{$src}" target="_blank" class="text-muted" {$attributes}><i class="fa fa-{$icon}"></i></a>
HTML;
		}
	}
}