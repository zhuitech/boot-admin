<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 链接
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Call extends AbstractDisplayer
{
    /**
     * Display method.
     *
     * @param null $object
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function display($object = null, $method = null, ... $params)
    {
        $row = $this->row;
        if ($row->$object && $method && method_exists($row->$object, $method)) {
            return call_user_func_array([$row->$object, $method], $params);
        }
    }
}