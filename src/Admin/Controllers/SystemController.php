<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;
use ZhuiTech\BootAdmin\Admin\Form\System\BasicForm;
use ZhuiTech\BootAdmin\Admin\Form\System\ConvertForm;
use ZhuiTech\BootAdmin\Admin\Form\System\PerformanceForm;
use ZhuiTech\BootAdmin\Admin\Form\System\SecurityForm;

class SystemController extends AdminController
{
	public function settings(Content $content)
	{
		$this->configContent($content, '系统设置');

		$forms = [
			'basic' => BasicForm::class,
			'performance' => PerformanceForm::class,
			'security' => SecurityForm::class,
		];

		return $content->body(Tab::forms($forms));
	}

	public function convertHelper(Content $content)
	{
		$this->configContent($content, '数据转换');
		return $content->body(new ConvertForm());
	}

	public function horizon(Content $content)
	{
		$url = route('horizon.index');
		$html = <<<EOT
<iframe src="$url" style="height: calc(100vh - 180px); width: calc(100vw - 260px); border: none;"></iframe>
EOT;
		$this->configContent($content, '队列管理');
		return $content->body($html);
	}
}