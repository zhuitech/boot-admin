<?php

namespace ZhuiTech\BootAdmin\Admin\Form\Fields;

class KeyValue extends \Encore\Admin\Form\Field\KeyValue
{
	protected function setupScript()
	{
		$this->script = <<<SCRIPT

$('.{$this->column}-add').on('click', function () {
	var group = $(this).closest('.fields-group');
    var tpl = group.find('template.{$this->column}-tpl').html();
    group.find('tbody.kv-{$this->column}-table').append(tpl);
});

$('tbody').on('click', '.{$this->column}-remove', function () {
    $(this).closest('tr').remove();
});

SCRIPT;
	}
}