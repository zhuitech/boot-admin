<?php

namespace ZhuiTech\BootAdmin\Admin\Form\Fields;

use ZhuiTech\BootAdmin\Admin\Actions\FieldSelector;

class Selector extends \Encore\Admin\Form\Field\Display
{
	protected $selectors = [];

	protected string $typeField = 'content_type';

	protected string $idField = 'content_id';

	protected $view = 'admin::form.display';

	public function selectors($selectors = [])
	{
		$this->selectors = $selectors;
	}

	public function fields($typeField, $idField)
	{
		$this->typeField = $typeField;
		$this->idField = $idField;
	}

	public function render()
	{
		$buttons = '';
		foreach ($this->selectors as $selector) {
			$buttons .= $selector->render();
		}

		$model = $this->form->model();
		$html = FieldSelector::buildSelectedHtml($model->{$this->typeField}, $model->{$this->idField});

		$this->value = <<<HTML
{$buttons}<br><div id="selected-contents">{$html}</div>
HTML;

		return parent::render();
	}
}