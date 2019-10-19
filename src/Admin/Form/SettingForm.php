<?php

namespace ZhuiTech\BootAdmin\Admin\Form;

use Encore\Admin\Widgets\Form;

class SettingForm extends Form
{
    public $title = 'Settings';

    public function handle()
    {
        $data = request()->except(['_token']);
        settings($data);
        admin_toastr('设置保存成功.');
        return back();
    }

    /**
     * 表单
     */
    public function form()
    {
        
    }

    /**
     * 表单默认值
     *
     * @return array
     */
    public function data()
    {
        $data = [];
        foreach ($this->fields as $field) {
            // 跳过系统字段
            if (in_array($field->column(), ['_form_'])) continue;
            $data[$field->column()] = settings($field->column());
        }
        return $data;
    }
}