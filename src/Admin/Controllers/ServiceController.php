<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use ZhuiTech\BootAdmin\Controllers\AdminController;
use ZhuiTech\BootLaravel\Helpers\ProxyClient;
use ZhuiTech\BootLaravel\Models\User;

class ServiceController extends AdminController
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
            return redirect(route('admin.svc.dashboard'));
        }

        // 传递用户身份
        $admin = new User([
            'id' => Admin::user()->getAuthIdentifier(),
            'type' => 'admins'
        ]);

        return ProxyClient::server('service')->as($admin)->pass();
    }

    /**
     * 代理API
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function api()
    {
        return ProxyClient::server('service')->pass();
    }
}