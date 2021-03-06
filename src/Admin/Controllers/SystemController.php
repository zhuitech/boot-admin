<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Admin;
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
		return $this->iframe($content, route('horizon.index'), '队列管理');
	}

	public function redirectTo(Content $content)
	{
		$url = request('target');
		Admin::script("window.location.href = \"$url\";");
		return $content->body('正在为您跳转...');
	}
}