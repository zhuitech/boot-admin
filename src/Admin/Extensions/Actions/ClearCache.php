<?php

namespace ZhuiTech\BootAdmin\Admin\Extensions\Actions;

use Artisan;
use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class ClearCache extends Action
{
	protected $selector = '.clear-cache';

	public function handle(Request $request)
	{
		Artisan::call('cache:clear');
		return $this->response()->success('清理完成')->refresh();
	}

	public function dialog()
	{
		$this->confirm('确认清除缓存');
	}

	public function html()
	{
		$url = admin_base_path();
		return <<<HTML
<li>
    <a class="clear-cache" href="$url#">
      <i class="fa fa-trash"></i>
      <span>缓存</span>
    </a>
</li>
HTML;
	}
}