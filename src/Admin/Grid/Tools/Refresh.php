<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Tools;

use Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class Refresh extends AbstractTool
{
	protected function script()
	{
		return <<<EOT
    $(function () {
        if (!$.admin.refreshCallback) {
            LA.refreshTimeoutId = null;
            LA.refreshCallback = function() {
                $.pjax.reload('#pjax-container');
            };
        }
        
        if ($.admin.refreshTimeoutId) {
            clearTimeout($.admin.refreshTimeoutId);
            $.admin.refreshTimeoutId = setTimeout($.admin.refreshCallback, 5000);
            $('.tools-live i').removeClass("fa-play fa-pause").addClass("fa-pause");
        }
        
        $('.tools-refresh').on('click', $.admin.refreshCallback);
        
        $('.tools-live').click(function() {
            $("i", this).toggleClass("fa-play fa-pause");
            
            if ($.admin.refreshTimeoutId) {
                clearTimeout($.admin.refreshTimeoutId);
            } else {
                $.admin.refreshTimeoutId = setTimeout($.admin.refreshCallback, 5000);
            }
        });
    });
EOT;
	}

	public function render()
	{
		Admin::script($this->script());
		$refresh = trans('admin.refresh');

		return <<<EOT
    <button type="button" class="btn btn-primary btn-sm tools-refresh"><i class="fa fa-refresh"></i> ${refresh}</button>
    <button type="button" class="btn btn-default btn-sm tools-live"><i class="fa fa-play"></i> </button>
EOT;
	}
}
