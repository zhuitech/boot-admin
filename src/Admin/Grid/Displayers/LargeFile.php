<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;
use Exception;
use Illuminate\Support\Facades\File;

/**
 * 大文件
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class LargeFile extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function display()
	{
		return static::render($this->value);
	}

	public static function render($value)
	{
		if ($value) {
			$src = storage_url(large_path($value));
			$name = basename($src);

			$ext = File::extension($name);
			if ($ext == 'mp4') {
				return <<<HTML
<a href='$src' target='_blank' class='text-muted'><i class="fa fa-video-camera"></i></a>
HTML;
			} else {
				return <<<HTML
<a href='$src' download='{$name}' target='_blank' class='text-muted'><i class="fa fa-download"></i></a>
HTML;
			}
		}
	}
}