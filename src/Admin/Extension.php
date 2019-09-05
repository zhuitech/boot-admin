<?php

namespace ZhuiTech\BootAdmin\Admin;

use Symfony\Component\Console\Output\ConsoleOutput;

class Extension extends \Encore\Admin\Extension
{
    public static function import()
    {
        $extension = static::getInstance();

        if ($menu = $extension->menu()) {
            static::createMenuTree($menu);
        }

        if ($permission = $extension->permission()) {
            if ($extension->validatePermission($permission)) {
                extract($permission);
                static::createPermission($name, $slug, $path);
            }
        }
    }

    public static function createMenuTree($root)
    {
        $menuModel = config('admin.database.menu_model');
        $lastOrder = $menuModel::max('order');

        // 根菜单
        if (empty($rootMenu = $menuModel::where(['title' => $root['title'], 'parent_id' => 0])->first())) {
            $rootMenu = $menuModel::create([
                'parent_id' => 0,
                'order' => $lastOrder++,
                'title' => $root['title'] ?? '',
                'icon' => $root['icon'] ?? '',
                'uri' => $root['uri'] ?? '',
            ]);
        }

        foreach ($root['children'] as $parent) {
            // 父菜单
            if (empty($parentMenu = $menuModel::where(['title' => $parent['title'] ?? '', 'parent_id' => $rootMenu->id])->first())) {
                $parentMenu = $menuModel::create([
                    'parent_id' => $rootMenu->id,
                    'order' => $lastOrder++,
                    'title' => $parent['title'] ?? '',
                    'icon' => $parent['icon'] ?? '',
                    'uri' => $parent['uri'] ?? '',
                ]);

                // 子菜单
                if (!empty($parent['children'])) {
                    foreach ($parent['children'] as $child) {
                        $menuModel::create([
                            'parent_id' => $parentMenu->id,
                            'order' => $lastOrder++,
                            'title' => $child['title'] ?? '',
                            'icon' => $child['icon'] ?? '',
                            'uri' => $child['uri'] ?? '',
                        ]);
                    }
                }

                $output = new ConsoleOutput();
                $output->writeln("<info>菜单[{$parent['title']}]创建成功</info>");
            }
        }
    }
}