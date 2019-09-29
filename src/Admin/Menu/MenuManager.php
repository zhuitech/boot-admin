<?php

namespace ZhuiTech\BootAdmin\Admin\Menu;

use ZhuiTech\BootAdmin\Admin\Menu\Menu as DataMenu;

class MenuManager
{
    private $dataMenu;
    private $allNodes;
    private $collectNodes;
    private $currentTopMenu;

    public function __construct(DataMenu $menu)
    {
        $this->dataMenu = $menu;
        $this->allNodes = $this->dataMenu->allNodes();
        $this->collectNodes = collect($this->allNodes);
        $this->currentTopMenu = $this->getCurrentTopMenu();
    }

    /**
     * Left sider-bar menu.
     *
     * @return array
     */
    public function topMenu()
    {
        $topMenus = $this->collectNodes->filter(function ($value, $key) {
            return 0 == $value['parent_id'];
        });

        $currentTopMenu = $this->currentTopMenu;
        $topMenus = $topMenus->map(function ($value, $key) use ($currentTopMenu) {
            if ($currentTopMenu['id'] == $value['id']) {
                $value['class'] = 'active';
            } else {
                $value['class'] = '';
            }

            return $value;
        })->all();

        return $topMenus;
    }

    public function getCurrentTopMenuByNode($currentNode)
    {
        if (0 == $currentNode['parent_id']) {
            return $currentNode;
        }

        $currentNode = $this->collectNodes->filter(function ($value, $key) use ($currentNode) {
            return $value['id'] == $currentNode['parent_id'];
        })->first();

        return $this->getCurrentTopMenuByNode($currentNode);
    }

    public function sideMenu()
    {
        $topMenuId = $this->currentTopMenu['id'];
        return $this->dataMenu->subTree($this->allNodes, $topMenuId);
    }

    /**
     * @return array
     */
    public function getCurrentTopMenu()
    {
        $prefix = trim(config('admin.route.prefix'), '/');
        $currentMenuUri = str_replace($prefix, '', request()->path());

        $currentMenu = $this->collectNodes->filter(function ($value, $key) use ($currentMenuUri) {
            return trim($value['uri'], '/') == trim($currentMenuUri, '/');
        })->first();

        $currentTopMenu = null;
        if ($currentMenu) {
            $currentTopMenu = $this->getCurrentTopMenuByNode($currentMenu);
        } else {
            $currentTopMenu = $this->getCurrentTopMenuByUri($currentMenuUri);
        }

        return $currentTopMenu;
    }

    /**
     * @param $currentMenuUri
     *
     * @return mixed
     */
    protected function getCurrentTopMenuByUri($currentMenuUri)
    {
        $topMenus = $this->collectNodes->filter(function ($value, $key) {
            return 0 == $value['parent_id'];
        });
        $topMenus->each(function ($item, $key) use ($currentMenuUri, &$currentTopMenu) {
            $currentUri = array_first(explode('/', trim($currentMenuUri, '/')));
            $itemUri = array_first(explode('/', trim($item['uri'], '/')));
            if ($currentUri == $itemUri) {
                $currentTopMenu = $item;
            }
        });

        return $currentTopMenu;
    }
}