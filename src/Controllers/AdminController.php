<?php

namespace ZhuiTech\BootAdmin\Controllers;

use Encore\Admin\Layout\Content;

class AdminController extends \Encore\Admin\Controllers\AdminController
{
    public function index(Content $content)
    {
        $breadcrumbs = $this->breadcrumb();
        $breadcrumbs[] = ['text' => $this->title(), 'left-menu-active' => $this->title()];

        return $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->breadcrumb(... $breadcrumbs)
            ->body($this->grid());
    }

    protected function breadcrumb()
    {
        return [];
    }
}