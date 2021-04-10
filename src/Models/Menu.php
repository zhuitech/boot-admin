<?php

namespace ZhuiTech\BootAdmin\Models;

use Eloquent;
use Encore\Admin\Auth\Database\Menu as BaseMenu;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\ZhuiTech\BootAdmin\Admin\Menu\Menu[] $children
 * @property-read \ZhuiTech\BootAdmin\Admin\Menu\Menu $parent
 * @property-read Collection|Role[] $roles
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu newModelQuery()
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu newQuery()
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu query()
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereBlank($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereCreatedAt($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereIcon($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereId($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereOrder($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereParentId($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu wherePermission($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereTitle($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereUpdatedAt($value)
 * @method static Builder|\ZhuiTech\BootAdmin\Admin\Menu\Menu whereUri($value)
 * @mixin Eloquent
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
