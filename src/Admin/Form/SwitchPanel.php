<?php

namespace ZhuiTech\BootAdmin\Admin\Form;

use Admin;
use Encore\Admin\Form;

class SwitchPanel
{
	/**
	 * 动态切换面板
	 *
	 * @param Form|\Encore\Admin\Widgets\Form $form
	 * @param callable $callback
	 * @param $name
	 * @param $value
	 */
	public static function create($form, callable $callback, $name, $value)
	{
		$html = <<<HTML
<div class="pane-$name pane-$name-$value" style="display: none;">
HTML;
		$form->html($html)->plain();
		$callback($form);
		$form->html('</div>')->plain();
	}

	/**
	 * 动态切换脚本
	 *
	 * @param $name
	 * @param string $type
	 */
	public static function script($name, $type = 'icheck')
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
        let value = $('select[name="$name"]').val();
        $('.pane-$name').hide();
        $('.pane-$name-' + value).show();
    };
    $('select[name="$name"]').change(function(){
        {$name}Changed();
    });
    {$name}Changed();
});
SCRIPT
			);
		}
	}
}