<?php

namespace ZhuiTech\BootAdmin\Admin\Menu;

use Encore\Admin\Auth\Database\Menu as BaseMenu;

/**
 * ZhuiTech\BootAdmin\Admin\Menu\Menu
 *
 * @property int $id
 * @property int $parent_id
 * @property int $order
 * @property string $title
 * @property string $icon
 * @property int $blank
 * @property string|null $uri
 * @property string|null $permission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\ZhuiTech\BootAdmin\Admin\Menu\Menu[] $children
 * @property-read \ZhuiTech\BootAdmin\Admin\Menu\Menu $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Encore\Admin\Auth\Database\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereBlank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereUri($value)
 * @mixin \Eloquent
 */
class Menu extends BaseMenu
{
    /**
     * @param $nodes
     * @param $parentId
     *
     * @return array
     */
    public function subTree($nodes, $parentId)
    {
        return $this->buildNestedArray($nodes, $parentId);
    }
}
