<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;
use ZhuiTech\BootAdmin\Admin\Form\System\BasicForm;
use ZhuiTech\BootAdmin\Admin\Form\System\ConvertForm;

class SystemController extends AdminController
{
    public function systemSetting(Content $content)
    {
        $this->configContent($content,'系统设置');

        $forms = [
            'basic'     => BasicForm::class,
        ];

        return $content->body(Tab::forms($forms));
    }

    public function convertHelper(Content $content)
    {
        $this->configContent($content,'数据转换');
        return $content->body(new ConvertForm());
    }
}