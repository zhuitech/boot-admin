<?php

namespace ZhuiTech\BootAdmin\Admin\Tree\Tools;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;

class Fix extends AbstractTool
{
    protected function script()
    {
        $url = route('admin.auth.menu.fix');

        return <<<EOT
        
        $('.menu-fix-visible').click(function () {
            let self = $(this);
            self.attr('disabled', 'disabled');
            
            $.post('{$url}', {
                _token: LA.token
            },
            function(data){
                self.removeAttr('disabled');
                if (data.status) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            });
        });

EOT;
    }

    public function render()
    {
        \Admin::script($this->script());

        return <<<EOT
            <a class="btn btn-warning btn-sm menu-fix-visible" title="修复可见性">
                <i class="fa fa-eye-slash"></i><span class="hidden-xs">&nbsp;修复可见性</span>
            </a>
EOT;
    }
}
