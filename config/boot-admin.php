<?php

return [
	/*
	 * --------------------------------------------------------------------------
	 * 后台设置
	 * --------------------------------------------------------------------------
	 *
	 * 其他设置项在config/admin.php
	 */

	// 版权声明
	'copyright' => 'Powered by <a href="https://www.zhuitech.com" target="_blank">ZHUITECH</a>',

	/*
	 * --------------------------------------------------------------------------
	 * 安全设置
	 * --------------------------------------------------------------------------
	 *
	 */

	// 启用验证码登录
    'sms_login' => env('SMS_LOGIN', false),

	// 系统异常通知手机号
	'fault_notify_mobile' => '',

	/*
	 * --------------------------------------------------------------------------
	 * 其他设置
	 * --------------------------------------------------------------------------
	 *
	 */
	'audit_switch' => false,
];
