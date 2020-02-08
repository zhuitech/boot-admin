<?php

namespace ZhuiTech\BootAdmin\Admin;

use Symfony\Component\Console\Output\ConsoleOutput;

class Extension extends \Encore\Admin\Extension
{
    public static function import()
    {
        $extension = static::getInstance();

        $menu = $extension->menu();
        if ($menu) {
            static::createMenuTree($menu);
        }

        $permission = $extension->permission();
        if ($permission) {
            if ($extension->validatePermission($permission)) {
                extract($permission);
                static::createPermission($name, $slug, $path);
            }
        }
    }

    public static function createMenuTree($root)
    {
        $menuModel = config('admin.database.menu_model');
        $output = new ConsoleOutput();

        // 根菜单
        if (empty($rootMenu = $menuModel::where(['title' => $root['title'], 'parent_id' => 0])->first())) {
            $rootOrder = $menuModel::where('parent_id', 0)->max('order');
            $rootMenu = $menuModel::create([
                'parent_id' => 0,
                'order' => ++$rootOrder,
                'title' => $root['title'] ?? '',
                'icon' => $root['icon'] ?? '',
                'uri' => $root['uri'] ?? '',
            ]);
        }

        $lastOrder = $rootMenu->order * 100;
        foreach ($root['children'] ?? [] as $parent) {
            // 父菜单
            if (empty($parentMenu = $menuModel::where(['title' => $parent['title'] ?? '', 'parent_id' => $rootMenu->id])->first())) {
                $parentMenu = $menuModel::create([
                    'parent_id' => $rootMenu->id,
                    'order' => $lastOrder++,
                    'title' => $parent['title'] ?? '',
                    'icon' => $parent['icon'] ?? '',
                    'uri' => $parent['uri'] ?? '',
                ]);

                $output->writeln("<info>菜单[{$parent['title']}]创建成功</info>");
            }

            // 子菜单
            foreach ($parent['children'] ?? [] as $child) {
                if (empty($childMenu = $menuModel::where(['title' => $child['title'] ?? '', 'parent_id' => $parentMenu->id])->first())) {
                    $menuModel::create([
                        'parent_id' => $parentMenu->id,
                        'order' => $lastOrder++,
                        'title' => $child['title'] ?? '',
                        'icon' => $child['icon'] ?? '',
                        'uri' => $child['uri'] ?? '',
                    ]);

                    $output->writeln("<info>菜单[{$child['title']}]创建成功</info>");
                }
            }
        }
    }
}