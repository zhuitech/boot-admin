<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\Actions;

class ButtonActions extends Actions
{
	/**
	 * Render view action.
	 *
	 * @return string
	 */
	protected function renderView()
	{
		return <<<EOT
<a href="{$this->getResource()}/{$this->getRouteKey()}" class="{$this->grid->getGridRowName()}-view btn btn-sm btn-default">
    <i class="fa fa-eye"></i> 查看
</a> 
EOT;
	}

	/**
	 * Render edit action.
	 *
	 * @return string
	 */
	protected function renderEdit()
	{
		return <<<EOT
<a href="{$this->getResource()}/{$this->getRouteKey()}/edit" class="{$this->grid->getGridRowName()}-edit btn btn-sm btn-success">
    <i class="fa fa-edit"></i> 编辑
</a> 
EOT;
	}

	/**
	 * Render delete action.
	 *
	 * @return string
	 */
	protected function renderDelete()
	{
		$this->setupDeleteScript();

		return <<<EOT
<a href="javascript:void(0);" data-id="{$this->getKey()}" class="{$this->grid->getGridRowName()}-delete btn btn-sm btn-danger">
    <i class="fa fa-trash"></i> 删除
</a> 
EOT;
	}
}