<?php

namespace ZhuiTech\BootAdmin\Admin\Form;

use Closure;
use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ZhuiTech\BootLaravel\Models\Model as ZhuiModel;

/**
 * 替代 Encore\Admin\Form
 * Class ModelForm
 * @package ZhuiTech\BootAdmin\Admin\Form
 */
class ModelForm extends Form
{
	/**
	 * @var array 待准备数据
	 */
	public $prepareValues = [];

	/**
	 * @var array 已准备数据
	 */
	public $preparedValues = [];

	/**
	 * 注册 prepared 回调
	 */

	public function prepared(Closure $callback)
	{
		return $this->registerHook('prepared', $callback);
	}

	protected function prepareInsert($inserts): array
	{
		$this->prepareValues = $inserts;
		$this->preparedValues = parent::prepareInsert($inserts);
		$this->callHooks('prepared');
		
		return $this->preparedValues;
	}

	protected function prepareUpdate(array $updates, $oneToOneRelation = false): array
	{
		$this->prepareValues = $updates;
		$this->preparedValues = parent::prepareUpdate($updates, $oneToOneRelation);
		$this->callHooks('prepared');
		
		return $this->preparedValues;
	}

	/**
	 * 提供外部修改关系数据
	 * @param $relation
	 * @param $value
	 */
	public function setRelationData($relation, $value)
	{
		$this->relations[$relation] = $value;
	}

	/**
	 * 重写 relationships 的更新规则，使其兼容动态关联关系
	 */

	protected function getRelationInputs($inputs = []): array
	{
		$relations = [];

		foreach ($inputs as $column => $value) {
			if (ZhuiModel::relationExists($this->model, $column) ||
				ZhuiModel::relationExists($this->model, $column = Str::camel($column))) {
				$relation = call_user_func([$this->model, $column]);

				if ($relation instanceof Relations\Relation) {
					$relations[$column] = $value;
				}
			}
		}

		return $relations;
	}

	protected function updateRelation($relationsData)
	{
		foreach ($relationsData as $name => $values) {
			if (!ZhuiModel::relationExists($this->model, $name)) {
				continue;
			}

			$relation = $this->model->$name();

			$oneToOneRelation = $relation instanceof Relations\HasOne
				|| $relation instanceof Relations\MorphOne
				|| $relation instanceof Relations\BelongsTo;

			$prepared = $this->prepareUpdate([$name => $values], $oneToOneRelation);

			if (empty($prepared)) {
				continue;
			}

			switch (true) {
				case $relation instanceof Relations\BelongsToMany:
				case $relation instanceof Relations\MorphToMany:
					if (isset($prepared[$name])) {
						$relation->sync($prepared[$name]);
					}
					break;
				case $relation instanceof Relations\HasOne:
				case $relation instanceof Relations\MorphOne:
					$related = $this->model->getRelationValue($name) ?: $relation->getRelated();

					foreach ($prepared[$name] as $column => $value) {
						$related->setAttribute($column, $value);
					}

					// save child
					$relation->save($related);
					break;
				case $relation instanceof Relations\BelongsTo:
				case $relation instanceof Relations\MorphTo:
					$related = $this->model->getRelationValue($name) ?: $relation->getRelated();

					foreach ($prepared[$name] as $column => $value) {
						$related->setAttribute($column, $value);
					}

					// save parent
					$related->save();

					// save child (self)
					$relation->associate($related)->save();
					break;
				case $relation instanceof Relations\HasMany:
				case $relation instanceof Relations\MorphMany:
					foreach ($prepared[$name] as $related) {
						/** @var Relations\HasOneOrMany $relation */
						$relation = $this->model->$name();

						$keyName = $relation->getRelated()->getKeyName();

						/** @var Model $child */
						$child = $relation->findOrNew(Arr::get($related, $keyName));

						if (Arr::get($related, static::REMOVE_FLAG_NAME) == 1) {
							$child->delete();
							continue;
						}

						Arr::forget($related, static::REMOVE_FLAG_NAME);

						foreach ($related as $colum => $value) {
							// 跳过自增字段
							if ($keyName == $colum && empty($value)) {
								continue;
							}
							
							$child->setAttribute($colum, $value);
						}

						$child->save();
					}
					break;
			}
		}
	}

	public function getRelations(): array
	{
		$relations = $columns = [];

		/** @var Field $field */
		foreach ($this->fields() as $field) {
			$columns[] = $field->column();
		}

		foreach (Arr::flatten($columns) as $column) {
			if (Str::contains($column, '.')) {
				list($relation) = explode('.', $column);

				if (ZhuiModel::relationExists($this->model, $relation) &&
					$this->model->$relation() instanceof Relations\Relation
				) {
					$relations[] = $relation;
				}
			} elseif (ZhuiModel::relationExists($this->model, $column) &&
				!method_exists(Model::class, $column)
			) {
				$relations[] = $column;
			}
		}

		return array_unique($relations);
	}
}