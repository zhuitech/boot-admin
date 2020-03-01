<?php

namespace ZhuiTech\BootAdmin\Admin\Form\System;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class ConvertForm extends Form
{
    public $title = '数据转换器';

    protected $result = '';

    public function handle(Request $request)
    {
        $input = $request->all();

        switch ($input['op']) {
            case 'json_php':
                $input['result'] = var_export_new(json_decode($input['content'], true), true);
                break;
            case 'json_format':
                $input['result'] = json_encode(json_decode($input['content'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
        }

        admin_success('操作成功');
        return back()->withInput($input);
    }

    public function form()
    {
        $this->textarea('content', '内容');
        $this->radio('op', '操作')->options([
            'json_php' => 'JSON转PHP',
            'json_format' => 'JSON格式化',
        ]);
        $this->textarea('result', '结果');
    }

    public function data()
    {
        return [
            'op' => 'json_php',
        ];
    }
}