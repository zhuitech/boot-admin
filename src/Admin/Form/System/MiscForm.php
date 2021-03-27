<?php

namespace ZhuiTech\BootAdmin\Admin\Form\System;

use ZhuiTech\BootAdmin\Admin\Form\SettingForm;
use ZhuiTech\BootLaravel\Helpers\FileHelper;

class MiscForm extends SettingForm
{
	public $title = '其他设置';

	protected $syncService = true;

	public function form()
	{
		$this->switch('boot-admin.audit_switch', '审核开关')->help('针对小程序审核规则，开启时隐藏部分功能');
	}
}