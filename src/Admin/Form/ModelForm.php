<?php

namespace ZhuiTech\BootAdmin\Admin\Form;

use Closure;
use Encore\Admin\Form;

class ModelForm extends Form
{
    public $preparedValues = [];

    /**
     * 注册prepread回调
     * @param Closure $callback
     * @return ModelForm
     */
    public function prepared(Closure $callback)
    {
        return $this->registerHook('prepared', $callback);
    }

    protected function prepareInsert($inserts): array
    {
        $this->preparedValues = parent::prepareInsert($inserts);
        $this->callHooks('prepared');
        return $this->preparedValues;
    }

    protected function prepareUpdate(array $updates, $oneToOneRelation = false): array
    {
        $this->preparedValues = parent::prepareUpdate($updates, $oneToOneRelation);
        $this->callHooks('prepared');
        return $this->preparedValues;
    }
}