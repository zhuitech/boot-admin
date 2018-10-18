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
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->menus();
        $this->permissions();
        $this->roles();
        $this->users();
    }

    private function permissions()
    {
        if (!Permission::where(['slug' => '*'])->first()) {
            Permission::insert([
                [
                    'name' => '所有权限',
                    'slug' => '*',
                    'http_method' => '',
                    'http_path' => '*',
                ], [
                    'name' => '控制台',
                    'slug' => 'dashboard',
                    'http_method' => '',
                    'http_path' => '/',
                ], [
                    'name' => '个人设置',
                    'slug' => 'auth.setting',
                    'http_method' => '',
                    'http_path' => '/auth/setting',
                ], [
                    'name' => '登录权限',
                    'slug' => 'auth.login',
                    'http_method' => '',
                    'http_path' => "/login\r\n/logout",
                ], [
                    'name' => '管理员管理',
                    'slug' => 'auth.users',
                    'http_method' => '',
                    'http_path' => '/auth/users*',
                ], [
                    'name' => '角色管理',
                    'slug' => 'auth.roles',
                    'http_method' => '',
                    'http_path' => '/auth/roles*',
                ], [
                    'name' => '权限管理',
                    'slug' => 'auth.permissions',
                    'http_method' => '',
                    'http_path' => '/auth/permissions*',
                ], [
                    'name' => '菜单管理',
                    'slug' => 'auth.menu',
                    'http_method' => '',
                    'http_path' => '/auth/menu*',
                ], [
                    'name' => '操作日志',
                    'slug' => 'auth.logs',
                    'http_method' => '',
                    'http_path' => '/auth/logs*',
                ], [
                    'name' => '系统日志',
                    'slug' => 'logs',
                    'http_method' => '',
                    'http_path' => '/logs*',
                ], [
                    'name' => '备份管理',
                    'slug' => 'backup',
                    'http_method' => '',
                    'http_path' => '/backup*',
                ], [
                    'name' => '计划任务',
                    'slug' => 'scheduling',
                    'http_method' => '',
                    'http_path' => '/scheduling*',
                ],
            ]);
        }
    }

    private function roles()
    {
        if (!Role::where(['slug' => 'administrator'])->first()) {
            $role = Role::create([
                'name' => '超级管理员',
                'slug' => 'administrator',
            ]);

            // 权限分配
            $role->permissions()->save(Permission::first());
        }

        if (!Role::where(['slug' => 'manager'])->first()) {
            $role = Role::create([
                'name' => '管理员',
                'slug' => 'manager',
            ]);

            // 权限分配
            $permissions = ['dashboard', 'auth.setting', 'auth.login',
                'auth.users', 'auth.roles', 'auth.permissions', 'auth.menu', 'auth.logs',
                'logs', 'backup', 'scheduling'
            ];
            foreach ($permissions as $permission) {
                $role->permissions()->save(Permission::where('slug', $permission)->first());
            }
        }

        if (!Role::where(['slug' => 'operator'])->first()) {
            $role = Role::create([
                'name' => '操作员',
                'slug' => 'operator',
            ]);

            // 权限分配
            $permissions = ['dashboard', 'auth.setting', 'auth.login',
            ];
            foreach ($permissions as $permission) {
                $role->permissions()->save(Permission::where('slug', $permission)->first());
            }
        }
    }

    private function users()
    {
        if (!Administrator::where(['username' => 'admin'])->first()) {
            $user = Administrator::create([
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'name' => '超级管理员',
                'mobile' => '18017250227'
            ]);

            // add role to user.
            $user->roles()->save(Role::where(['slug' => 'administrator'])->first());
        }

        if (!Administrator::where(['username' => 'manager'])->first()) {
            $user = Administrator::create([
                'username' => 'manager',
                'password' => bcrypt('manager'),
                'name' => '管理员',
            ]);

            // add role to user.
            $user->roles()->save(Role::where(['slug' => 'manager'])->first());
        }

        if (!Administrator::where(['username' => 'operator'])->first()) {
            $user = Administrator::create([
                'username' => 'operator',
                'password' => bcrypt('operator'),
                'name' => '操作员',
            ]);

            // add role to user.
            $user->roles()->save(Role::where(['slug' => 'operator'])->first());
        }
    }

    private function menus()
    {
        if (!Menu::where(['title' => '系统', 'parent_id' => 0])->first()) {
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
                'title' => '安全',
                'icon' => 'fa-tasks',
                'uri' => '',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '管理员',
                'icon' => 'fa-users',
                'uri' => '/auth/users',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '角色管理',
                'icon' => 'fa-user',
                'uri' => '/auth/roles',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '权限管理',
                'icon' => 'fa-ban',
                'uri' => '/auth/permissions',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '菜单管理',
                'icon' => 'fa-bars',
                'uri' => '/auth/menu',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '操作日志',
                'icon' => 'fa-history',
                'uri' => '/auth/logs',
            ]);

            $parent = Menu::create([
                'parent_id' => $root->id,
                'order' => $lastOrder++,
                'title' => '工具',
                'icon' => 'fa-gears',
                'uri' => '',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '脚手架',
                'icon' => 'fa-keyboard-o',
                'uri' => '/helpers/scaffold',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '数据库',
                'icon' => 'fa-database',
                'uri' => '/helpers/terminal/database',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '命令行',
                'icon' => 'fa-terminal',
                'uri' => '/helpers/terminal/artisan',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '路由',
                'icon' => 'fa-list-alt',
                'uri' => '/helpers/routes',
            ]);

            $parent = Menu::create([
                'parent_id' => $root->id,
                'order' => $lastOrder++,
                'title' => 'Redis',
                'icon' => 'fa-database',
                'uri' => '/redis',
            ]);

            $parent = Menu::create([
                'parent_id' => $root->id,
                'order' => $lastOrder++,
                'title' => '系统日志',
                'icon' => 'fa-database',
                'uri' => '/logs',
            ]);

            $parent = Menu::create([
                'parent_id' => $root->id,
                'order' => $lastOrder++,
                'title' => '备份管理',
                'icon' => 'fa-copy',
                'uri' => '/backup',
            ]);

            $parent = Menu::create([
                'parent_id' => $root->id,
                'order' => $lastOrder++,
                'title' => '计划任务',
                'icon' => 'fa-clock-o',
                'uri' => '/scheduling',
            ]);
        }
    }
}
