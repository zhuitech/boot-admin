<?php

namespace ZhuiTech\BootAdmin\Admin\Form;

use Admin;
use Encore\Admin\Form\Field\Currency;
use Encore\Admin\Form\Field\Decimal;
use Encore\Admin\Form\Field\File;
use Encore\Admin\Form\Field\Number;
use Encore\Admin\Form\Field\Rate;
use Encore\Admin\Widgets\Form;

class SettingForm extends Form
{
    public $title = 'Settings';

    public function handle()
    {
        $data = [];
        foreach ($this->fields as $field) {
            $value = request($field->column());

            // 跳过没有上传文件
            if ($field instanceof File && empty($value)) {
                continue;
            }

            // 预处理值
            $value = $field->prepare($value);

            // 跳过没有修改的 config
            if (in_array($field->column(), config('backend.settings') ?? [])) {
                if ($value == config($field->column())) {
                    continue;
                }
            }

            // 放入修改列表
            $data[$field->column()] = $value;
        }

        // 提交修改
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

            // 获取默认值
            $default = '';
            if ($field instanceof Number || $field instanceof Currency || $field instanceof Rate || $field instanceof Decimal) {
                $default = 0;
            }

            // 使用config默认值
            if (in_array($field->column(), config('backend.settings') ?? [])) {
                $default = config($field->column());
            }

            $data[$field->column()] = settings($field->column(), $default);
        }
        return $data;
    }

    /**
     * 动态切换面板
     *
     * @param callable $callback
     * @param $name
     * @param $value
     */
    public function switchPane(callable $callback, $name, $value)
    {
        $html = <<<HTML
<div class="pane-$name pane-$name-$value" style="display: none;">
HTML;
        $this->html($html)->plain();
        $callback($this);
        $this->html('</div>')->plain();
    }

    /**
     * 动态切换脚本
     *
     * @param $name
     * @param string $type
     */
    public function switchScript($name, $type = 'icheck')
    {
        if ($type == 'icheck') {
            Admin::script(<<<SCRIPT
$(function () {
    var {$name}Changed = function() {
        let value = $('input[name="$name"]:checked').val();
        $('.pane-$name').hide();
        $('.pane-$name-' + value).show();
    };
    $('input[name="$name"]').on('ifChecked', function(){
        {$name}Changed();
    });
    {$name}Changed();
});
SCRIPT
            );
        }

        if ($type == 'select') {
            Admin::script(<<<SCRIPT
$(function () {
    var {$name}Changed = function() {
        let value = $('input[name="$name"]').val();
        $('.pane-$name').hide();
        $('.pane-$name-' + value).show();
    };
    $('input[name="$name"]').change(function(){
        {$name}Changed();
    });
    {$name}Changed();
});
SCRIPT
            );
        }
    }
}