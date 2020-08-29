<?php

namespace ZhuiTech\BootAdmin\Admin\Excel;

use Maatwebsite\Excel\Concerns\FromCollection;

class ExportCollection implements FromCollection
{
	private $data;

	public function __construct($data, $title)
	{
		array_unshift($data, $title);
		$this->data = collect($data);
	}

	public function collection()
	{
		return $this->data;
	}
}