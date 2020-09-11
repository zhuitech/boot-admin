<?php

namespace ZhuiTech\BootAdmin\Admin\Form\System;

use ZhuiTech\BootAdmin\Admin\Form\SettingForm;
use ZhuiTech\BootLaravel\Helpers\FileHelper;

class BasicForm extends SettingForm
{
	public $title = '基础设置';

	public function form()
	{
		$this->text('admin.name', '系统名称')->help('显示在登录页面的系统名称。');
		$this->file('admin.login_logo', '登录页LOGO')->help('设置登录页LOGO。')->dir(FileHelper::dir('admin'))->uniqueName()->removable();
		$this->image('admin.login_background_image', '登录页背景图')->dir(FileHelper::dir('admin'))->uniqueName()->help('设置登录页背景图。')->removable();

		$this->text('admin.title', '页面标题')->help('显示在所有后台页面的标题。');
		$this->text('admin.logo', '大标识')->help('设置左侧导航展开状态显示的标识，可以使用HTML。');
		$this->text('admin.logo-mini', '小标识')->help('设置左侧导航收拢状态显示的标识，可以使用HTML。');

		$skins = [
			"skin-blue", "skin-blue-light", "skin-yellow", "skin-yellow-light",
			"skin-green", "skin-green-light", "skin-purple", "skin-purple-light",
			"skin-red", "skin-red-light", "skin-black", "skin-black-light"
		];
		$skins = array_combine($skins, $skins);
		$this->select('admin.skin', '显示主题')->options($skins)->required()->help('设置后台显示样式。');

		$this->text('boot-admin.copyright', '版权声明')->help('显示在页面底部的版权信息说明');
	}
}