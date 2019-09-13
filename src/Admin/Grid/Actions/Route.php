<?php
/**
 * Created by PhpStorm.
 * User: breeze
 * Date: 2019-04-26
 * Time: 13:30
 */

namespace ZhuiTech\BootAdmin\Admin\Grid\Actions;

use Encore\Admin\Actions\RowAction;

/**
 * 链接
 *
 * Class Link
 * @package ZhuiTech\BootAdmin\Admin\Grid\Actions
 */
class Route extends RowAction
{
    private $route;
    private $resourceKey;

    public function __construct($route, $name = '跳转', $resourceKey = 'id')
    {
        $this->route = $route;
        $this->name = $name;
        $this->resourceKey = $resourceKey;

        parent::__construct();
    }

    public function href()
    {
        return route($this->route, [$this->resourceKey => $this->getKey()]);
    }
}