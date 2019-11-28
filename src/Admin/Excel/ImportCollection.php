<?php

namespace ZhuiTech\BootAdmin\Admin\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportCollection implements ToCollection
{
    /**
     * @var Collection
     */
    public $data;

    public function collection(Collection $rows)
    {
        $this->data = $rows;
    }
}