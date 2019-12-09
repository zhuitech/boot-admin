<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ZhuiTech\BootAdmin\Admin\Controllers\AdminController;
use ZhuiTech\BootAdmin\Admin\Menu\Menu;
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

        // 不能直接访问内页
        if ($request->method() == 'GET' && !$request->ajax()) {
            $top = admin_url(with(\BackendMenu::getCurrentTopMenu())['uri']);
            $current = request()->getPathInfo();

            if ($top != $current) {
                // 通过当前顶级菜单做pjax跳转
                //return redirect($top . '?' . http_build_query(['_active' => $current]));
            } else {
                // 回到后台首页，防止循环跳转
                return redirect(admin_url('/'));
            }
        }

        // 传递用户身份
        $user = new User();
        $user->id = Admin::user()->getAuthIdentifier();
        $user->type = 'admins';

        return ProxyClient::server('service')->as($user)->pass();
    }
}