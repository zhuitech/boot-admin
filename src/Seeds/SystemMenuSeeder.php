<?php

/*
 * This file is part of ibrand/backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZhuiTech\BootAdmin\Seeds;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;

class SystemMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        if (Menu::where(['title' => '系统', 'parent_id' => 0])->first()) {
            return;
        }

        $rootOrder = Menu::where('parent_id', 0)->max('order');
        $root = Menu::create([
            'parent_id' => 0,
            'order' => ++$rootOrder,
            'title' => '系统',
            'icon' => 'fa-cogs',
            'uri' => '/',
        ]);

        $lastOrder = $rootOrder * 100;
        $parent = Menu::create([
            'parent_id' => $root->id,
            'order' => $lastOrder++,
            'title' => '控制台',
            'icon' => 'fa-bar-chart',
            'uri' => '/',
        ]);

        $parent = Menu::create([
            'parent_id' => $root->id,
            'order' => $lastOrder++,
            'title' => '权限管理',
            'icon' => 'fa-tasks',
            'uri' => '',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '管理员',
            'icon' => 'fa-users',
            'uri' => 'auth/users',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '角色管理',
            'icon' => 'fa-user',
            'uri' => 'auth/roles',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '权限管理',
            'icon' => 'fa-ban',
            'uri' => 'auth/permissions',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '菜单管理',
            'icon' => 'fa-bars',
            'uri' => 'auth/menu',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '操作日志',
            'icon' => 'fa-history',
            'uri' => 'auth/logs',
        ]);

        $parent = Menu::create([
            'parent_id' => $root->id,
            'order' => $lastOrder++,
            'title' => '小工具',
            'icon' => 'fa-gears',
            'uri' => '',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '脚手架',
            'icon' => 'fa-keyboard-o',
            'uri' => 'helpers/scaffold',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '数据库',
            'icon' => 'fa-database',
            'uri' => 'helpers/terminal/database',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '命令行',
            'icon' => 'fa-terminal',
            'uri' => 'helpers/terminal/artisan',
        ]);
        Menu::create([
            'parent_id' => $parent->id,
            'order' => $lastOrder++,
            'title' => '路由',
            'icon' => 'fa-list-alt',
            'uri' => 'helpers/routes',
        ]);

        $parent = Menu::create([
            'parent_id' => $root->id,
            'order' => $lastOrder++,
            'title' => 'Redis',
            'icon' => 'fa-database',
            'uri' => 'redis',
        ]);

        $parent = Menu::create([
            'parent_id' => $root->id,
            'order' => $lastOrder++,
            'title' => '系统日志',
            'icon' => 'fa-database',
            'uri' => 'logs',
        ]);

        $parent = Menu::create([
            'parent_id' => $root->id,
            'order' => $lastOrder++,
            'title' => '备份管理',
            'icon' => 'fa-copy',
            'uri' => 'backup',
        ]);

        $parent = Menu::create([
            'parent_id' => $root->id,
            'order' => $lastOrder++,
            'title' => '计划任务',
            'icon' => 'fa-clock-o',
            'uri' => 'scheduling',
        ]);
    }
}
