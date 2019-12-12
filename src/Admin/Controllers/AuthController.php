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
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return view('admin::login');
    }

    public function postLogin(Request $request)
    {
        $username = !$this->isMail(request($this->username())) ? 'username' : 'email';
        $credentials = $request->only([$this->username(), 'password', 'code']);

        if (config('backend.sms_login')) {
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
                'data' => $user->only(['mobile']),
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

    protected function settingForm()
    {
        $class = config('admin.database.users_model');

        $form = new Form(new $class());

        $form->display('username', trans('admin.username'));
        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'))->dir(FileHelper::dir('admin'))->uniqueName();
        $form->password('password', trans('admin.password'))->rules('confirmed|required');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->setAction(admin_url('auth/setting'));

        $form->ignore(['password_confirmation']);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        $form->saved(function () {
            admin_toastr(trans('admin.update_succeeded'));

            return redirect(admin_url('auth/setting'));
        });

        return $form;
    }
}
