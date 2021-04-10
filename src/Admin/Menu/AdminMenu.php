<?php

namespace ZhuiTech\BootAdmin\Admin\Menu;

use Illuminate\Support\Collection;
use ZhuiTech\BootAdmin\Models\Menu as DataMenu;

class AdminMenu
{
	private $dataMenu;
	private $allNodes;
	private $allNodesCollection;
	private $currentTopMenu;
	private $currentNode;

	public function __construct(DataMenu $menu)
	{
		$this->dataMenu = $menu;
		$this->allNodes = $this->dataMenu->allNodes();
		$this->allNodesCollection = collect($this->allNodes);
		$this->currentTopMenu = $this->getCurrentTopMenu();
	}

	/**
	 * Top menu
	 *
	 * @return Collection
	 */
	public function topMenu()
	{
		$topMenus = $this->allNodesCollection->filter(function ($value, $key) {
			return 0 == $value['parent_id'];
		});

		$currentTopMenu = $this->currentTopMenu;
		$topMenus = $topMenus->map(function ($value, $key) use ($currentTopMenu) {
			if ($currentTopMenu && $currentTopMenu['id'] == $value['id']) {
				$value['class'] = 'active';
			} else {
				$value['class'] = '';
			}

			return $value;
		})->all();

		return $topMenus;
	}

	/**
	 * Left sider-bar menu.
	 *
	 * @return array
	 */
	public function sideMenu()
	{
		$topMenuId = $this->currentTopMenu['id'] ?? 0;
		return $this->dataMenu->subTree($this->allNodes, $topMenuId);
	}

	/**
	 * 获取当前页面最近菜单项
	 * @return mixed
	 */
	public function getCurrentNode()
	{
		if (empty($this->currentNode)) {
			$prefix = trim(config('admin.route.prefix'), '/');
			$currentUri = str_replace($prefix, '', request()->path());
			$currentUriWithQuery = trim("{$currentUri}?" . request()->getQueryString(), '/');

			$currentNode = null;
			$slices = explode('/', trim($currentUri, '/'));

			while (empty($currentNode) && count($slices) > 0) {
				foreach ($this->allNodesCollection as $item) {
					if (trim($item['uri'], '/') == $currentUriWithQuery // 带参数匹配
						|| trim(explode('?', $item['uri'])[0], '/') == implode('/', $slices) // 去参数匹配
					) {
						$currentNode = $item;
						break;
					}
				}

				if ($currentNode) {
					break;
				}

				array_pop($slices);
			};

			$this->currentNode = $currentNode;
		}

		return $this->currentNode;
	}

	/**
	 * @return array
	 */
	public function getCurrentTopMenu()
	{
		return $this->getCurrentTopMenuByNode($this->getCurrentNode());
	}

	/**
	 * @param $currentNode
	 * @return mixed
	 */
	public function getCurrentTopMenuByNode($currentNode)
	{
		if (empty($currentNode)) {
			return null;
		}

		if (0 == $currentNode['parent_id']) {
			return $currentNode;
		}

		$currentNode = $this->allNodesCollection->filter(function ($value, $key) use ($currentNode) {
			return $value['id'] == $currentNode['parent_id'];
		})->first();

		return $this->getCurrentTopMenuByNode($currentNode);
	}
}
