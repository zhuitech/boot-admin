<?php

namespace ZhuiTech\BootAdmin\Console;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Console\Command;
use ZhuiTech\BootAdmin\Seeds\AdminTableSeeder;
use Encore\Admin\Auth\Database\Administrator;

class AdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '安装后台模块';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 初始化数据
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
                    'name' => '基础权限',
                    'slug' => 'admin.dashboard',
                    'http_method' => '',
                    'http_path' => '/
/auth/setting
/login
/logout
/_handle_action_',
                ], [
                    'name' => '授权管理',
                    'slug' => 'admin.auth',
                    'http_method' => '',
                    'http_path' => '/auth*',
                ], [
                    'name' => '系统日志',
                    'slug' => 'admin.logs',
                    'http_method' => '',
                    'http_path' => '/logs*',
                ],
            ]);

            $this->line("<info>Permissions insert successfully.</info>");
        }
    }

    private function roles()
    {
        if (!Role::where(['slug' => 'administrator'])->first()) {
            $role = Role::create([
                'name' => '系统管理员',
                'slug' => 'administrator',
            ]);

            // 权限分配
            $role->permissions()->save(Permission::first());

            $this->line("<info>Role administrator insert successfully.</info>");
        }

        if (!Role::where(['slug' => 'manager'])->first()) {
            $role = Role::create([
                'name' => '普通管理员',
                'slug' => 'manager',
            ]);

            // 权限分配
            $permissions = ['admin.dashboard', 'admin.logs'];
            foreach ($permissions as $permission) {
                $role->permissions()->save(Permission::where('slug', $permission)->first());
            }

            $this->line("<info>Role manager insert successfully.</info>");
        }
    }

    private function users()
    {
        if (!Administrator::where(['username' => 'admin'])->first()) {
            $user = Administrator::create([
                'username' => 'admin',
                'password' => bcrypt('letmein2019'),
                'name' => '系统管理员',
                'mobile' => '18017250227'
            ]);

            // add role to user.
            $user->roles()->save(Role::where(['slug' => 'administrator'])->first());

            $this->line("<info>User admin insert successfully.</info>");
        }

        if (!Administrator::where(['username' => 'manager'])->first()) {
            $user = Administrator::create([
                'username' => 'manager',
                'password' => bcrypt('letmein2019'),
                'name' => '普通管理员',
            ]);

            // add role to user.
            $user->roles()->save(Role::where(['slug' => 'manager'])->first());

            $this->line("<info>User manager insert successfully.</info>");
        }
    }

    private function menus()
    {
        if (!Menu::where(['title' => '系统', 'parent_id' => 0])->first()) {
            $rootOrder = 1;
            $root = Menu::create([
                'parent_id' => 0,
                'order' => $rootOrder,
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
                'title' => '文件',
                'icon' => 'fa-file',
                'uri' => '/media',
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
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => 'Redis',
                'icon' => 'fa-database',
                'uri' => '/redis',
            ]);
            /*Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '备份管理',
                'icon' => 'fa-copy',
                'uri' => '/backup',
            ]);*/
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '计划任务',
                'icon' => 'fa-clock-o',
                'uri' => '/scheduling',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '系统配置',
                'icon' => 'fa-gears',
                'uri' => '/system/settings',
            ]);
            Menu::create([
                'parent_id' => $parent->id,
                'order' => $lastOrder++,
                'title' => '系统日志',
                'icon' => 'fa-database',
                'uri' => '/logs',
            ]);

            $this->line("<info>Menus insert successfully.</info>");
        }
    }
}
