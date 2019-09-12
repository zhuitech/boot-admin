<?php

namespace ZhuiTech\BootAdmin\Admin\Menu;

/**
 * Class Menu.
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MenuManager::class;
    }
}
