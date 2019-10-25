<?php

namespace ZhuiTech\BootAdmin\Admin\Grid;

use Encore\Admin\Grid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Tab extends \Encore\Admin\Widgets\Tab
{
    /**
     * Tab constructor.
     * @param Grid $grid
     * @param $field
     * @param $options array
     * @param bool $all
     * @param callable $filter
     */
    public function __construct(Grid $grid, $field, $options, $all = true, callable $filter = null)
    {
        parent::__construct();

        if ($all) {
            $options = ['' => '全部'] + $options;
        }
        $current = request($field, array_first(array_keys($options)));

        if (empty($filter)) {
            $filter = function ($query, $field, $key) {
                return $query->where($field, $key);
            };
        }

        $titles = [];
        foreach ($options as $key => $value) {
            $query = $grid->model()->getQueryBuilder();
            if ($key === '') {
                $count = $query->count();
            } else {
                $count = $filter($query, $field, $key)->count();
            }
            $titles[$key] = "$value ($count)";
        }

        foreach ($options as $key => $value) {
            $title = $titles[$key];
            if ("$current" === "$key") {
                if ($key !== '') {
                    $filter($grid->model(), $field, $key);
                }
                $this->add($title, $grid->render(), true);
            } else {
                $params = request()->query();
                $params[$field] = $key;
                $url = request()->getPathInfo() . '?' . http_build_query($params);
                $this->addLink($title, $url);
            }
        }
    }
}