<?php

namespace ZhuiTech\BootAdmin\Admin\Actions;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use ZhuiTech\BootAdmin\Admin\Grid\Displayers\Thumbnail;
use ZhuiTech\BootAdmin\Services\BootAdmin;
use ZhuiTech\BootLaravel\Services\BootLaravel;

class FieldSelector extends SelectorAction
{
	protected $title;

	protected $model;

	protected $selectable;

	protected $multiple = false;

	protected string $typeField;

	protected string $idField;

	protected $modalAlias;

	public function __construct()
	{
		$this->modalAlias = BootLaravel::morphAlias($this->model);
		parent::__construct();
	}

	public function fields($typeField, $idField)
	{
		$this->typeField = $typeField;
		$this->idField = $idField;
		return $this;
	}

	public function actionScript()
	{
		return <<<SCRIPT
			$('input[name={$this->typeField}]').val('{$this->modalAlias}');
			$('input[name={$this->idField}]').val(selected[0]);
SCRIPT;
	}

	public function handle(Request $request)
	{
		$selected = $request->get('selected', []);
		$html = self::buildSelectedHtml($this->modalAlias, $selected);
		return $this->response()->html($html);
	}

	public static function buildSelectedHtml($alias, $selected)
	{
		if (empty($alias) || empty($selected)) {
			return '';
		}

		if (!is_array($selected)) {
			$selected = [$selected];
		}

		$class = Relation::$morphMap[$alias];

		$html = '';
		foreach ($selected as $id) {
			if ($content = $class::find($id)) {
				$thumbnail = Thumbnail::render($content->image, null, 50);
				$title = "标题：{$content->title}";
				$type = "类型：" . BootLaravel::morphTitle($alias);

				$html .= BootAdmin::imageText($thumbnail, [$title, $type]);
			}
		}

		return $html;
	}
}