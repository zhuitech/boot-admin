<?php

namespace ZhuiTech\BootAdmin\Admin\Menu;

use Encore\Admin\Auth\Database\Menu as BaseMenu;

/**
 * Class Menu.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
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
