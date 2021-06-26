<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ZhuiTech\BootLaravel\Helpers\FileHelper;
use ZhuiTech\BootLaravel\Remote\Service\SMS;

class AuthController extends \Encore\Admin\Controllers\AuthController
{
	public function postLogin(Request $request)
	{
		$this->loginValidator($request->all())->validate();

		$credentials = $request->only([$this->username(), 'password']);
		$remember = $request->get('remember', false);

		if (config('boot-admin.sms_login')) {
			$admin = Administrator::where("username", request('username'))->where('status', 1)->first();
			if (!$admin or !$admin->mobile) {
				return redirect()->back()->withInput()->withErrors(['username' => '账号不存在或未绑定手机']);
			}

			$mobile = $admin->mobile;
			if (!request('code')) {
				return redirect()->back()->withInput()->withErrors(['code' => '验证码不能为空']);
			}

			if (!SMS::check($mobile, request('code'))) {
				return redirect()->back()->withInput()->withErrors(['code' => '验证码不正确']);
			}
		}

		if ($this->guard()->attempt($credentials, $remember)) {
			return $this->sendLoginResponse($request);
		}

		return back()->withInput()->withErrors([
			$this->username() => $this->getFailedLoginMessage(),
		]);
	}

	public function getMobile(Request $request)
	{
		if ($user = Administrator::where("username", $request->username)->where('status', 1)->first() and $user->mobile) {
			return response()->json([
				'data' => $user->only(['mobile']),
				'status' => true,
			]);
		}

		return response()->json([
			'msg' => '管理员账号不存在或未绑定手机号码',
			'status' => false,
		]);
	}
}
