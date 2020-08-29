<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Admin;
use AdminMenu;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ZhuiTech\BootLaravel\Helpers\ProxyClient;
use ZhuiTech\BootLaravel\Models\User;

class ServiceProxyController extends AdminController
{
	public function index(Content $content)
	{
		return $content
			->title('Dashboard')
			->description('Description...')
			->row(function (Row $row) {
			});
	}

	/**
	 * 代理后台页面
	 *
	 * @return Response|RedirectResponse|Redirector
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
			$top = admin_url(with(AdminMenu::getCurrentTopMenu())['uri']);
			$current = request()->getPathInfo();
			if ($top != $current) {
				// 通过当前顶级菜单做pjax跳转
				return redirect($top . '?' . http_build_query(['load' => $current]));
			}
		}

		// 修改 csrf_token
		$content = (string)$response->getBody();
		$content = preg_replace('/"_token":"(\w|\d)+"/i', '"_token":"' . csrf_token() . '"', $content);
		$content = preg_replace('/_token:\'(\w|\d)+\'/i', '_token:\'' . csrf_token() . '\'', $content);

		return response($content, $response->getStatusCode(), $response->getHeaders());
	}
}