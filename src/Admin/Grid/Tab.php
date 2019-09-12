<?php

namespace ZhuiTech\BootAdmin\Admin\Grid;

use Encore\Admin\Grid;

class Tab extends \Encore\Admin\Widgets\Tab
{
    /**
     * Tab constructor.
     * @param Grid $grid
     * @param $field
     * @param $options array
     * @param $route
     */
    public function __construct(Grid $grid, $field, $options, $route)
    {
        $current = request($field, array_key_first($options));
        
        foreach ($options as $key => $value) {
            $count = $grid->model()->getOriginalModel()->where($field, $key)->count();
            $title = "$value ($count)";
            if ($current == $key) {
                $grid->model()->where($field, $current);
                $this->add($title, $grid->render(), true);
            } else {
                $url = route($route, [$field => $key]);
                $this->addLink($title, $url);
            }
        }
    }
}