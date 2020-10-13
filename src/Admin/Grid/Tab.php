<?php

namespace ZhuiTech\BootAdmin\Admin\Grid;

use Encore\Admin\Grid;
use Illuminate\Support\Arr;

class Tab extends \Encore\Admin\Widgets\Tab
{
	/**
	 * Tab constructor.
	 * 
	 * @param Grid $grid
	 * @param string $field 注意分页的字段名不能在filter中出现，否则数量统计出错
	 * @param $options array
	 * @param bool $all
	 * @param callable $filter
	 */
	public function __construct(Grid $grid, $field, $options, $all = true, callable $filter = null)
	{
		parent::__construct();

		if ($all) {
			$options = ['_all' => '全部'] + $options;
		}
		$current = request($field, Arr::first(array_keys($options)));

		if (empty($filter)) {
			$filter = function ($query, $field, $key) {
				return $query->where($field, $key);
			};
		}

		$titles = [];
		
		// 统计每页数据
		foreach ($options as $key => $value) {
			// 基于当前filter获取模型对象，保留filter的参数
			$model = $grid->getFilter()->getModel();
			$query = $model->getQueryBuilder();

			if (is_array($value)) {
				// 使用自定义filter
				$count = $value['filter']($query, $field, $key)->count();
				$title1 = $value['title'];
			} else {
				if ($key === '_all') {
					// 全部结果
					$count = $query->count();
				} else {
					// 分页结果
					$count = $filter($query, $field, $key)->count();
				}
				$title1 = $value;
			}

			$titles[$key] = "$title1 ($count)";
		}

		// 创建tab页
		foreach ($options as $key => $value) {
			$title = $titles[$key];

			if ("$current" === "$key") {
				// 当前页
				if ($key !== '_all') {
					$filter1 = is_array($value) ? $value['filter'] : $filter;
					$filter1($grid->model(), $field, $key);
				}

				$this->add($title, $grid->render(), true);
			} else {
				// 其他页
				$params = request()->query();
				$params[$field] = $key;
				$url = request()->getPathInfo() . '?' . http_build_query($params);
				$this->addLink($title, $url);
			}
		}
	}
}