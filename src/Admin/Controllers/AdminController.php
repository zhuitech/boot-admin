<?php

namespace ZhuiTech\BootAdmin\Admin\Controllers;

use AdminMenu;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\DropdownActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ZhuiTech\BootAdmin\Admin\Form\ModelForm;
use ZhuiTech\BootAdmin\Admin\Form\SwitchPanel;
use ZhuiTech\BootAdmin\Admin\Grid\Actions\PopupEdit;
use ZhuiTech\BootAdmin\Admin\Grid\Tools\CreateButton;
use ZhuiTech\BootAdmin\Admin\Grid\Tools\PopupCreate;

class AdminController extends \Encore\Admin\Controllers\AdminController
{
	/**
	 * 定义扩展点
	 * @var null
	 */
	protected $extension = null;

	/**
	 * 获取扩展模块
	 * @return AdminControllerExtender[]
	 */
	protected function getExtensions()
	{
		if ($this->extension) {
			return app()->tagged($this->extension);
		}

		return [];
	}

	/**
	 * 获取资源ID
	 *
	 * @return mixed
	 */
	protected function getKey()
	{
		$parameters = request()->route()->parameters;
		return Arr::last($parameters);
	}

	/**
	 * 更新
	 *
	 * @param int $id
	 * @return Response
	 */
	public function update($id)
	{
		$id = $this->getKey();
		return parent::update($id);
	}

	/**
	 * 删除
	 *
	 * @param int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$id = $this->getKey();
		return parent::destroy($id);
	}

	/**
	 * 列表
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function index(Content $content)
	{
		parent::index($content);
		return $this->configContent($content, $this->title(), $this->description['index'] ?? trans('admin.list'));
	}

	/**
	 * 详情
	 *
	 * @param mixed $id
	 * @param Content $content
	 * @return Content
	 */
	public function show($id, Content $content)
	{
		$id = $this->getKey();
		parent::show($id, $content);
		return $this->configContent($content, $this->title(), $this->description['show'] ?? trans('admin.show'), __('admin.show'));
	}

	/**
	 * 编辑
	 *
	 * @param mixed $id
	 * @param Content $content
	 * @return Content
	 */
	public function edit($id, Content $content)
	{
		$id = $this->getKey();
		parent::edit($id, $content);
		return $this->configContent($content, $this->title(), $this->description['edit'] ?? trans('admin.edit'), __('admin.edit'));
	}

	/**
	 * 创建
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function create(Content $content)
	{
		parent::create($content);
		return $this->configContent($content, $this->title(), $this->description['create'] ?? trans('admin.create'), __('admin.create'));
	}

	/**
	 * 设置内容
	 *
	 * @param Content $content
	 * @param null $title
	 * @param null $description
	 * @param null $action
	 * @return Content
	 */
	protected function configContent(Content $content, $title = null, $description = null, $action = null)
	{
		$breadcrumbs = [];
		$path = Str::replaceFirst(admin_base_path(), '', request()->getPathInfo());

		// 一级
		$top = AdminMenu::getCurrentTopMenu();
		if (!empty($top)) {
			array_push($breadcrumbs, ['text' => $top['title'], 'url' => $top['uri']]);
		}

		// 二级：菜单树中最近菜单项
		$node = AdminMenu::getCurrentNode();
		if (!empty($node)) {
			array_push($breadcrumbs, ['text' => $node['title'], 'url' => $node['uri']]);

			// 三级：子项列表
			$pathes = explode('/', trim(Str::replaceFirst($node['uri'], '', $path), '/'));
			if (count($pathes) >= 2 && !in_array($pathes[1], ['create', 'edit'])) {
				array_push($breadcrumbs, ['text' => $title, 'url' => implode('/', [$node['uri'], $pathes[0], $pathes[1]])]);
			}
		}

		// 三级/四级：create/edit/view
		if (!empty($action)) {
			array_push($breadcrumbs, ['text' => $action]);
		}

		return $content->title($title)->description($description)->breadcrumb(... $breadcrumbs);
	}

	/**
	 * 设置表格
	 *
	 * @param Grid $grid
	 * @param string $mode
	 * @param array $options
	 * @return Grid
	 */
	protected function configGrid(Grid $grid, $mode = 'editable', $options = [])
	{
		$extensions = $this->getExtensions();
		$grid->setActionClass(DropdownActions::class)->disableExport()
			->actions(function (Grid\Displayers\Actions $actions) use ($options, $mode, $extensions) {
				switch ($mode) {
					case 'editable': // 允许增删改
						$actions->disableView();
						break;
					case 'nocreate': // 不许新增
						$actions->disableView();
						break;
                    case 'noedit': // 不许编辑
                        $actions->disableEdit()->disableView();
                        break;
					case 'editonly': // 只许编辑
						$actions->disableDelete()->disableView();
						break;
					case 'readonly': // 只许查看
						$actions->disableDelete()->disableEdit();
						break;
					case 'removable': // 只许删除
						$actions->disableEdit()->disableView();
						break;
					case 'popup': // 弹出框模式
						$actions->disableView()->disableEdit();
						$actions->add(new PopupEdit($options['edit'] ?? null));
						break;
				}

				if (isset($options['actionsCallback'])) {
					$options['actionsCallback']($actions);
				}

				// 扩展
				foreach ($extensions as $extension) {
					$extension->gridActions($actions);
				}
			})
			->batchActions(function (Grid\Tools\BatchActions $batch) use ($options, $mode, $extensions) {
				switch ($mode) {
					case 'editable': // 允许增删改
						break;
					case 'nocreate': // 不许新增
						break;
                    case 'noedit': // 不许编辑
                        break;
					case 'editonly': // 只许编辑
						$batch->disableDelete();
						break;
					case 'readonly': // 只许查看
						$batch->disableDelete();
						break;
					case 'removable': // 只许删除
						break;
					case 'popup': // 弹出框模式
						break;
				}

				if (isset($options['batchCallback'])) {
					$options['batchCallback']($batch);
				}

				// 扩展
				foreach ($extensions as $extension) {
					$extension->gridBatchActions($batch);
				}
			})
			->filter(function (Grid\Filter $filter) use ($options, $mode, $extensions) {
				switch ($mode) {
					case 'editable': // 允许增删改
						$filter->disableIdFilter();
						break;
					case 'nocreate': // 不许新增
						$filter->disableIdFilter();
						break;
                    case 'noedit': // 不许编辑
                        $filter->disableIdFilter();
                        break;
					case 'editonly': // 只许编辑
						$filter->disableIdFilter();
						break;
					case 'readonly': // 只许查看
						$filter->disableIdFilter();
						break;
					case 'removable': // 只许删除
						$filter->disableIdFilter();
						break;
					case 'popup': // 弹出框模式
						$filter->disableIdFilter();
						break;
				}

				if (isset($options['filterCallback'])) {
					$options['filterCallback']($filter);
				}

				// 扩展
				foreach ($extensions as $extension) {
					$extension->gridFilter($filter);
				}
			});

		$grid->tools(function (Grid\Tools $tools) use ($options, $mode, $grid, $extensions) {
			switch ($mode) {
				case 'editable': // 允许增删改
					if ($grid->showCreateBtn()) {
						$tools->append(new CreateButton($grid));
					}
					break;
				case 'nocreate': // 不许新增
					break;
                case 'noedit': // 不许编辑
	                if ($grid->showCreateBtn()) {
		                $tools->append(new CreateButton($grid));
	                }
                    break;
				case 'editonly': // 只许编辑
					break;
				case 'readonly': // 只许查看
					break;
				case 'removable': // 只许删除
					break;
				case 'popup': // 弹出框模式
					if ($grid->showCreateBtn()) {
						$tools->append(new PopupCreate($grid, $options['create'] ?? null));
					}
					break;
			}

			if (isset($options['toolsCallback'])) {
				$options['toolsCallback']($tools);
			}

			// 扩展
			foreach ($extensions as $extension) {
				$extension->gridTools($tools);
			}
		});

		$grid->disableCreateButton();

		// 扩展
		foreach ($extensions as $extension) {
			$extension->grid($grid);
		}

		// 默认排序
		if (empty($grid->model()->orders)) {
			$key = $grid->model()->getOriginalModel()->getKeyName();
			$grid->model()->orderBy($key, 'desc');
		}

		return $grid;
	}

	/**
	 * 设置表单
	 *
	 * @param Form $form
	 * @param string $mode
	 * @param array $options
	 * @return Form
	 */
	protected function configForm(Form $form, $mode = 'page', $options = [])
	{
		$extensions = $this->getExtensions();
		$form->tools(function (Form\Tools $tools) use ($options, $mode, $extensions) {
			switch ($mode) {
				case 'page':
					$tools->disableView();
					break;
				case 'popup':
					$tools->disableView()->disableDelete()->disableList();
					break;
			}

			if (isset($options['toolsCallback'])) {
				$options['toolsCallback']($tools);
			}

			// 扩展
			foreach ($extensions as $extension) {
				$extension->formTools($tools);
			}
		});

		$form->footer(function (Form\Footer $footer) use ($options, $mode, $extensions) {
			switch ($mode) {
				case 'page':
					$footer->disableViewCheck()->disableEditingCheck()->disableCreatingCheck();
					break;

				case 'popup':
					$footer->disableViewCheck()->disableEditingCheck()->disableCreatingCheck()->disableReset()->disableSubmit();
					break;
			}

			if (isset($options['footerCallback'])) {
				$options['footerCallback']($footer);
			}

			// 扩展
			foreach ($extensions as $extension) {
				$extension->formFooter($footer);
			}
		});

		// 扩展
		foreach ($extensions as $extension) {
			$extension->form($form);
		}

		return $form;
	}

	/**
	 * 设置详情
	 *
	 * @param Show $show
	 * @param string $mode
	 * @param array $options
	 * @return Show
	 */
	protected function configShow(Show $show, $mode = 'box', $options = [])
	{
		$extensions = $this->getExtensions();
		$show->panel()->tools(function (Show\Tools $tools) use ($options, $mode, $extensions) {
			switch ($mode) {
				case 'box':
					$tools->disableEdit()->disableList()->disableDelete();
					break;
				case 'readonly':
					$tools->disableEdit()->disableDelete();
					break;
			}

			if (isset($options['toolsCallback'])) {
				$options['toolsCallback']($tools);
			}

			// 扩展
			foreach ($extensions as $extension) {
				$extension->showTools($tools);
			}
		});

		switch ($mode) {
			case 'box':
				$show->panel()->title('');
				break;
			case 'readonly':
				$show->field('created_at', '创建时间');
				$show->field('updated_at', '更新时间');
				break;
		}

		// 扩展
		foreach ($extensions as $extension) {
			$extension->show($show);
		}

		return $show;
	}

	/**
	 * @param ModelForm $form
	 * @param $select_name
	 * @param $option_name
	 * @param $options
	 */
	protected function selectForm(ModelForm $form, $select_name, $option_name, $options)
	{
		foreach ($options as $key => $config) {
			SwitchPanel::create($form, function (ModelForm $form) use ($option_name, $key, $config) {
				// 创建子表单
				$option_form = "{$option_name}_{$key}";
				$form->embeds($option_form, $config['name'], function (Form\EmbeddedForm $form) use ($config) {
					foreach ($config['options'] as $field => $value) {
						if (!is_array($value)) {
							$value = ['type' => 'text', 'title' => $value];
						}

						$value += ['title' => '', 'help' => '', 'type' => 'text'];
						switch ($value['type']) {
							case 'textarea':
								$form_item = $form->textarea($field, $value['title']);
								break;
							case 'select':
								$form_item = $form->select($field, $value['title'])->options($value['options']);
								break;
							case 'file':
								$value += ['disk' => 'local', 'dir' => 'local'];
								$form_item = $form->file($field, $value['title'])->disk($value['disk'])->dir($value['dir']);
								break;
							default:
								$form_item = $form->text($field, $value['title']);
								break;
						}

						if (!empty($form_item) && !empty($value['help'])) {
							$form_item->help($value['help']);
						}
					}
				});
			}, $select_name, $key);
		}

		$form->editing(function (ModelForm $form) use ($select_name, $option_name) {
			$model = $form->model();
			// 生成子表单数据
			$option_form = "{$option_name}_{$model->$select_name}";
			$model->$option_form = $model->$option_name;
		});

		$form->prepared(function (ModelForm $form) use ($select_name, $option_name, $options) {
			$prepared = $form->preparedValues;

			// 复制到实际字段
			$option_form = "{$option_name}_{$prepared[$select_name]}";
			$prepared[$option_name] = $prepared[$option_form];

			// 删除子表单数据
			foreach ($options as $key => $config) {
				$option_form = "{$option_name}_{$key}";
				unset($prepared[$option_form]);
			}

			$form->preparedValues = $prepared;
		});

		SwitchPanel::script($select_name, 'select');
	}

	public function iframe(Content $content, $url, $title)
	{
		$html = <<<EOT
<iframe src="$url" style="height: calc(100vh - 180px); width: calc(100vw - 260px); border: none;"></iframe>
EOT;
		$this->configContent($content, $title);
		return $content->body($html);
	}
}
