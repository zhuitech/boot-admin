<?php

namespace ZhuiTech\BootAdmin\Admin\Form\System;

use ZhuiTech\BootAdmin\Admin\Form\SettingForm;

class PerformanceForm extends SettingForm
{
	public $title = '性能设置';

	protected $syncService = true;

	public function form()
	{
		$this->switch('boot-laravel.cdn_status', '启用CDN')->help('开启后，JS、CSS、图片、视频等静态资源将使用CDN域名加载');
		$this->text('boot-laravel.cdn_url', 'CDN地址')->help('包含http(s)://前缀，后面不要带斜杠');
		$this->text('boot-laravel.cdn_replace_url', 'CDN替换地址')->help('要被CDN替换掉的地址前缀，包含http(s)://前缀，后面不要带斜杠');
		$this->number('boot-laravel.concurrent_request_limit', '并发请求用户数')->help('在登录、下单、秒杀等环节，限制最大并发请求的用户数，0为不限制');
		$this->switch('boot-laravel.pressure_test', '压测模式')->help('开启后，禁用流控设置');
	}
}