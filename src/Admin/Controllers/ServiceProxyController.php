<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use ZhuiTech\BootLaravel\Helpers\ProxyClient;
use ZhuiTech\BootLaravel\Models\User;

class ServiceProxyController extends AdminController
{
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(function (Row $row) {});
    }

    /**
     * 代理后台页面
     *
     * @return \GuzzleHttp\Psr7\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function admin()
    {
        $request = request();

        // 传递用户身份
        $user = new User();
        $user->id = Admin::user()->getAuthIdentifier();
        $user->type = 'admins';
        $response = ProxyClient::server('service')->as($user)->pass();

        // 把直接访问svc页面的请求通过顶级菜单转发
        if ($request->method() == 'GET' && !$request->ajax() && $response->getStatusCode() == 200) {
            $top = admin_url(with(\BackendMenu::getCurrentTopMenu())['uri']);
            $current = request()->getPathInfo();
            if ($top != $current) {
                // 通过当前顶级菜单做pjax跳转
                return redirect($top . '?' . http_build_query(['load' => $current]));
            } 
        }

        return $response;
    }
}