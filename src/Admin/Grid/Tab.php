<?php

namespace ZhuiTech\BootAdmin\Admin\Grid;

use Encore\Admin\Grid;
use Illuminate\Support\Arr;

class Tab extends \Encore\Admin\Widgets\Tab
{
    /**
     * Tab constructor.
     * @param Grid $grid
     * @param $field
     * @param $options array
     * @param $route
     */
    public function __construct(Grid $grid, $field, $options, $all = true)
    {
        if ($all) {
            $options = ['' => '全部'] + $options;
        }

        $current = request($field, array_key_first($options));

        $titles = [];
        foreach ($options as $key => $value) {
            $query = $grid->model()->getQueryBuilder();
            if ($key === '') {
                $count = $query->count();
            } else {
                $count = $query->where($field, $key)->count();
            }
            $titles[$key] = "$value ($count)";
        }

        foreach ($options as $key => $value) {
            $title = $titles[$key];
            if ("$current" === "$key") {
                if ($key !== '') {
                    $grid->model()->where($field, $key);
                }
                $this->add($title, $grid->render(), true);
            } else {
                $url = request()->getPathInfo() . '?' . http_build_query([$field => $key]);
                $this->addLink($title, $url);
            }
        }
    }
}