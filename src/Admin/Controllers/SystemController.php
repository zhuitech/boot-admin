<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;
use ZhuiTech\BootAdmin\Admin\Form\System\BasicForm;

class SystemController extends AdminController
{
    public function settings(Content $content)
    {
        $this->configContent($content,'系统设置');

        $forms = [
            'basic'     => BasicForm::class,
        ];

        return $content->body(Tab::forms($forms));
    }
}