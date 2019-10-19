<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree\Tools;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Str;
use ZhuiTech\BootAdmin\Admin\Tree\Tools\Fix;
use ZhuiTech\BootLaravel\Controllers\RestResponse;

class MenuController extends \Encore\Admin\Controllers\MenuController
{
    use RestResponse;

    public function index(Content $content)
    {
        return $content
            ->title(trans('admin.menu'))
            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $tree = $this->treeView();

                // 添加工具栏
                $tree->tools(function (Tools $tools) {
                    $tools->add(new Fix());
                });

                $row->column(6, $tree->render());

                // 快捷新建菜单
                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('auth/menu'));

                    $menuModel = config('admin.database.menu_model');
                    $permissionModel = config('admin.database.permissions_model');
                    $roleModel = config('admin.database.roles_model');

                    $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->icon('icon', trans('admin.icon'))->default('')->help($this->iconHelp());
                    $form->text('uri', trans('admin.uri'));
                    $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    if ((new $menuModel())->withPermission()) {
                        $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
                    }
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
    }

    public function form()
    {
        $form = parent::form();
        $form->builder()->field('icon')->rules(function () {
            return '';
        });

        return $form;
    }

    public function fix()
    {
        // 计算每个角色的可访问路径
        $roles = [];
        foreach (Role::all() as $role) {
            $roles[$role->id] = [];
            foreach ($role->permissions as $permission) {
                $roles[$role->id] = array_merge($roles[$role->id], explode("\n", $permission->http_path));
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

        return $this->success();
    }
}