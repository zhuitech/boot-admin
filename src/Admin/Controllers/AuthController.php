<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use ZhuiTech\Services\SMS\Services\SMS;

class AuthController extends \Encore\Admin\Controllers\AuthController
{
	public function getLogin()
	{
		if ($this->guard()->check()) {
			return redirect($this->redirectPath());
		}

		$img = config('admin.login_background_image');
		$wallpaper = $img ? storage_url($img) : ('/vendor/boot-admin/img/wallpapers/shanghai.jpg');

		return view($this->loginView, compact('wallpaper'));
	}

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

			if (!request('code')) {
				return redirect()->back()->withInput()->withErrors(['code' => '验证码不能为空']);
			}

			if (!SMS::check(['mobile' => $admin->mobile, 'verify_code' => request('code')])) {
				return redirect()->back()->withInput()->withErrors(['code' => '验证码错误']);
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
