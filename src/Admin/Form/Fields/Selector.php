<?php

namespace ZhuiTech\BootAdmin\Admin\Form\Fields;

use ZhuiTech\BootAdmin\Admin\Actions\FieldSelector;

class Selector extends \Encore\Admin\Form\Field\Display
{
	/**
	 * @var array | FieldSelector[]
	 */
	protected $selectors = [];

	protected string $typeField = 'content_type';

	protected string $idField = 'content_id';

	protected $view = 'admin::form.display';

	public function selectors($selectors = [])
	{
		$this->selectors = $selectors;
		foreach ($this->selectors as $selector) {
			$selector->target("#{$this->id}-selected");
			$selector->fields($this->typeField, $this->idField);
		}
		return $this;
	}

	public function fields($typeField, $idField)
	{
		$this->typeField = $typeField;
		$this->idField = $idField;
		return $this;
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
{$buttons}<br><div id="{$this->id}-selected">{$html}</div>
HTML;

		return parent::render();
	}
}