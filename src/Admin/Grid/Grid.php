<?php

namespace ZhuiTech\BootAdmin\Admin\Grid;

use Illuminate\Support\Collection;

class Grid extends \Encore\Admin\Grid
{
    protected $queriedCallbacks = [];

    /**
     * @var Collection null
     */
    public $queriedCollection = null;

    public function queried(callable $callback)
    {
        $this->queriedCallbacks[] = $callback;
        return $this;
    }

    protected function callQueriedCallback()
    {
        foreach ($this->queriedCallbacks as $callback) {
            call_user_func($callback, $this);
        }
    }

    protected function applyQuery()
    {
        $this->queriedCollection = parent::applyQuery();

        $this->callQueriedCallback();

        return $this->queriedCollection;
    }
}