<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Str;
use ZhuiTech\BootAdmin\Controllers\AdminController;
use ZhuiTech\BootLaravel\Helpers\RestClient;

class ServiceController extends AdminController
{
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(function (Row $row) {
            });
    }

    public function admin()
    {
        $request = request();
        $user = Admin::user();
        $contentType = $request->header('Content-Type');

        $options = [
            'headers' => [
                'X-User' => $user->id,
                'X-FORWARDED-PROTO' => $request->getScheme(),
                'X-FORWARDED-HOST' => $request->server('HTTP_HOST'),
            ],
            'query' => $request->query(),
        ];

        foreach (['X-PJAX', 'X-PJAX-Container'] as $item) {
            if ($request->hasHeader($item)) {
                $options['headers'][$item] = $request->header($item);
            }
        }

        if (Str::contains($contentType, 'multipart/form-data')) {
            $multipart = RestClient::server()->createMultipart($request->all());
            $options['multipart'] = $multipart;
        } else {
            $options['headers']['Content-Type'] = $contentType;
            $options['body'] = $request->getContent();
        }

        $result = RestClient::server('service')
            ->plain()
            ->request($request->path(), $request->method(), $options);

        return $result;
    }

    public function api()
    {
    }
}