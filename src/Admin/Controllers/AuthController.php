<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ZhuiTech\BootLaravel\Remote\Service\SMS;

class AuthController extends \Encore\Admin\Controllers\AuthController
{
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return view('admin::admin-login-ibrand');
    }

    public function postLogin(Request $request)
    {
        $username = !$this->isMail(request($this->username())) ? 'username' : 'email';
        $credentials = $request->only([$this->username(), 'password', 'code']);

        if (config('ibrand.backend.sms_login')) {
            $admin = Administrator::where("$username", request('username'))->where('status', 1)->first();
            if (!$admin or !$admin->mobile) {
                return redirect()->back()->withInput()->withErrors(['username' => '账号不存在或未绑定手机']);
            }

            $mobile = $admin->mobile;
            $credentials_code = [
                'mobile' => $mobile,
                'verifyCode' => request('code'),
            ];

            if (!request('code')) {
                return redirect()->back()->withInput()->withErrors(['code' => '验证码不能为空']);
            }

            if (!SMS::check($mobile, \request('code'))) {
                return redirect()->back()->withInput()->withErrors(['code' => '验证码不正确']);
            }

            unset($credentials['code']);
        }

        $validator = Validator::make($credentials, [
            $this->username() => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $new_credentials[$username] = $credentials[$this->username()];
        $new_credentials['password'] = $credentials['password'];

        if ($this->guard()->attempt($new_credentials)) {
            return $this->sendLoginResponse($request);
        }

        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }

    public function getMobile(Request $request)
    {
        $username = !$this->isMail(request($this->username())) ? 'username' : 'email';

        if ($user = Administrator::where("$username", $request->username)->where('status', 1)->first() and $user->mobile) {
            return response()->json([
                'data' => $user,
                'status' => true,
            ]);
        }

        return response()->json([
            'msg' => '管理员账号不存在或未绑定手机号码',
            'status' => false,
        ]);
    }

    protected function isMail($Argv)
    {
        $RegExp = '/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
        return preg_match($RegExp, $Argv) ? $Argv : false;
    }
}
