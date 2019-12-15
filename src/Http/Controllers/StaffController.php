<?php

namespace ZhuiTech\BootAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ZhuiTech\BootAdmin\Models\Staff;
use ZhuiTech\BootLaravel\Controllers\RestResponse;
use ZhuiTech\BootLaravel\Helpers\RestClient;

class StaffController extends Controller
{
    use RestResponse;

    /**
     * 登录
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function auth(Request $request)
    {
        $data = $this->validate($request, [
            'mobile' => 'required',
            'verify_code' => 'required',
        ]);

        // 请求验证
        $result = RestClient::server('service')->post('api/svc/sms/check', $data);
        if (!$result['status']) {
            return response()->json($result);
        }
        
        // 用户是否存在
        $user = Staff::whereMobile($data['mobile'])->first();
        if (empty($user)) {
            return $this->fail('用户不存在');
        }
        
        return $this->success([
            'user' => Arr::except($user->toArray(), ['password', 'remember_token', 'created_at', 'updated_at']),
            'access_token' => $user->createToken('staff')->accessToken,
        ]);
    }
}