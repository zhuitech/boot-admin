<?php

namespace ZhuiTech\BootAdmin\Admin\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportCollection implements ToCollection
{
	/**
	 * @var Collection
	 */
	public $data = [];

	public function collection(Collection $rows)
	{
		foreach ($rows as $row) {
			$this->data[] = $row->toArray();
		}
	}

	/**
	 * 转换标题行为健名
	 * @return array
	 */
	public function treatTitle()
	{
		$result = [];
		$titles = [];

		foreach ($this->data as $i => $row) {
			if ($i === 0) {
				$titles = $row;
				continue;
			}

			$item = [];
			foreach ($row as $j => $cell) {
				$item[$titles[$j]] = $cell;
			}
			$result[] = $item;
		}

		return $result;
	}
}