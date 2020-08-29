<?php

namespace ZhuiTech\BootAdmin\Admin\Form;

use Encore\Admin\Form\Field\Currency;
use Encore\Admin\Form\Field\Decimal;
use Encore\Admin\Form\Field\File;
use Encore\Admin\Form\Field\Number;
use Encore\Admin\Form\Field\Rate;
use Encore\Admin\Widgets\Form;
use Illuminate\Support\Str;

class SettingForm extends Form
{
	public $title = 'Settings';

	/**
	 * 字段名映射
	 * @var array
	 */
	protected $keyMapping = [];

	public function handle()
	{
		$data = [];
		foreach ($this->fields as $field) {
			$value = request($field->column());

			// 跳过没有上传文件
			if ($field instanceof File && empty($value)) {
				continue;
			}

			// 预处理值
			$value = $field->prepare($value);

			// 获取key
			$key = $this->keyMapping[$field->column()] ?? $field->column();

			// 跳过没有修改的 config
			if (Str::contains($key, '.')) {
				$original = config($key);
				$new = $value;

				if (is_string($original)) {
					$original = str_replace("\r\n", "\n", $original);
					$new = str_replace("\r\n", "\n", $value);
				}

				if ($new == $original) {
					continue;
				}
			}

			// 放入修改列表
			$data[$key] = $value;
		}

		// 提交修改
		settings($data);
		admin_toastr('设置保存成功.');
		return back();
	}

	/**
	 * 表单
	 */
	public function form()
	{

	}

	/**
	 * 表单默认值
	 *
	 * @return array
	 */
	public function data()
	{
		$data = [];
		foreach ($this->fields as $field) {
			// 跳过系统字段
			if (in_array($field->column(), ['_form_'])) continue;

			// 默认值
			$default = '';
			if ($field instanceof Number || $field instanceof Currency || $field instanceof Rate || $field instanceof Decimal) {
				$default = 0;
			}

			// 获取key
			$key = $this->keyMapping[$field->column()] ?? $field->column();

			// 使用config默认值
			if (Str::contains($key, '.')) {
				$default = config($key, $default);
			}

			// 加载设置
			$data[$field->column()] = settings($key, $default);
		}
		return $data;
	}
}