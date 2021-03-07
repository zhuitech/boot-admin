<?php

namespace ZhuiTech\BootAdmin\Admin\Actions;

use Admin;
use Encore\Admin\Actions\Action;

class SelectorAction extends Action
{
	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $modalID;

	/**
	 * @var string
	 */
	protected $selectable;

	/**
	 * @var bool
	 */
	protected $multiple = false;

	/**
	 * @var string
	 */
	protected $target;

	public function html()
	{
		$class = $this->getElementClass();
		$this->modalID = sprintf('modal-selector-%s', $this->getElementClass());

		$this->addScript()->addHtml()->addStyle();

		return <<<HTML
		<div class="btn-group" style="margin-right: 5px">
            <a class="btn btn-sm btn-success selector-button $class">$this->title</a>
        </div>
HTML;
	}

	/**
	 * @param int $multiple
	 *
	 * @return string
	 */
	protected function getLoadUrl($multiple = 0)
	{
		$selectable = str_replace('\\', '_', $this->selectable);
		$args = [$multiple];

		return route('admin.handle-selectable', compact('selectable', 'args'));
	}

	/**
	 * @return $this
	 */
	public function addHtml()
	{
		$trans = [
			'choose' => admin_trans('admin.choose'),
			'cancal' => admin_trans('admin.cancel'),
			'submit' => admin_trans('admin.submit'),
		];

		$html = <<<HTML
<div class="modal fade selector-action" id="{$this->modalID}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 5px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">{$trans['choose']}</h4>
      </div>
      <div class="modal-body">
      <div class="loading text-center">
        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{$trans['cancal']}</button>
        <button type="button" class="btn btn-primary submit">{$trans['submit']}</button>
      </div>
    </div>
  </div>
</div>
HTML;
		Admin::html($html);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function addStyle()
	{
		$style = <<<'STYLE'
.selector-action.modal tr {
    cursor: pointer;
}
.selector-action.modal .box {
    border-top: none;
    margin-bottom: 0;
    box-shadow: none;
}

.selector-action.modal .loading {
    margin: 50px;
}
STYLE;

		Admin::style($style);

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function addScript()
	{
		$parameters = json_encode($this->parameters());

		$script = <<<SCRIPT
;(function () {
    var modal = $('#{$this->modalID}');
    var selected = [];

    // open modal
    $('{$this->selector($this->selectorPrefix)}').click(function (e) {
        $('#{$this->modalID}').modal('show');
        e.preventDefault();
    });

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($.inArray($(el).val().toString(), selected) >=0 ) {
                    $(el).iCheck('toggle');
                }
            });
        });
    };

    var update = function (callback) {
        if (selected.length > 0) {
            var data = {selected: selected};
            var target = $('{$this->target}');
            Object.assign(data, {$parameters});
            {$this->actionScript()}
            {$this->buildActionPromise()}
            {$this->handleActionPromise()}
        }

        callback();
    };

    modal.on('show.bs.modal', function (e) {
        load("{$this->getLoadUrl($this->multiple)}");
    }).on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('click', 'tr', function (e) {
        $(this).find('input.select').iCheck('toggle');
        e.preventDefault();
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        e.preventDefault();
        return false;
    }).on('ifChecked', 'input.select', function (e) {
        if (selected.indexOf($(this).val()) < 0) {
            selected.push($(this).val());
        }
    }).on('ifUnchecked', 'input.select', function (e) {
           var val = $(this).val();
           var index = selected.indexOf(val);
           if (index !== -1) {
               selected.splice(index, 1);
           }
    }).find('.modal-footer .submit').click(function () {
        update(function () {
            modal.modal('toggle');
        });
    });
})();
SCRIPT;

		\Encore\Admin\Admin::script($script);

		return $this;
	}
}