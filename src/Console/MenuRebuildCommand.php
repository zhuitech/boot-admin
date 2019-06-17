<?php

namespace ZhuiTech\BootAdmin\Console;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MenuRebuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:menu-rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重建后台菜单';

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
        // 计算每个角色的可访问路径
        $roles = [];
        foreach (Role::all() as $role) {
            $roles[$role->id] = [];
            foreach ($role->permissions as $permission) {
                $roles[$role->id] = array_merge($roles[$role->id], explode("\r\n", $permission->http_path));
            }
        }

        // 遍历菜单
        $menus = Menu::all();
        foreach ($menus as $menu) {
            if (empty($menu->uri)) {
                continue;
            }

            $menu->roles()->detach();
            foreach ($roles as $rid => $pattern) {
                if (Str::is($pattern, '/' . trim($menu->uri, '/'))) {
                    $menu->roles()->save(Role::find($rid));
                }
            }
        }
    }
}
