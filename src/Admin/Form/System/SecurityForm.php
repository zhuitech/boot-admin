<?php

namespace ZhuiTech\BootAdmin\Admin\Form\System;

use ZhuiTech\BootAdmin\Admin\Form\SettingForm;
use ZhuiTech\BootLaravel\Helpers\FileHelper;

class SecurityForm extends SettingForm
{
	public $title = '安全设置';

	public function form()
	{
		$this->switch('boot-admin.sms_login', '短信验证登录')->help('开启后，后台需要短信验证码和密码双因子验证后才可登录');
		$this->text('boot-admin.fault_notify_mobile', '异常通知手机号码')->help('系统发生异常时，会以短信的方式发送通知到这些手机号码');
	}
}