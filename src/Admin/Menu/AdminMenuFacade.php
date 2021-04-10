<?php

namespace ZhuiTech\BootAdmin\Admin\Menu;

/**
 * Class Menu.
 */
class AdminMenuFacade extends \Illuminate\Support\Facades\Facade
{
	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return AdminMenu::class;
	}
}
