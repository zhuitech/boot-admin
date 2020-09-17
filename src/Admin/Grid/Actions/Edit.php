<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Actions;

use Encore\Admin\Actions\RowAction;

class Edit extends RowAction
{
	/**
	 * @var bool
	 */
	private $pjax;

	/**
	 * Edit constructor.
	 * @param bool $pjax
	 */
	public function __construct($pjax = true)
	{
		$this->pjax = $pjax;
	}

	/**
     * @return array|null|string
     */
    public function name()
    {
        return __('admin.edit');
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}/edit";
    }

	public function render()
	{
		if ($href = $this->href()) {
			$pjax = $this->pjax ? '' : 'no-pjax';
			return "<a href='{$href}' {$pjax}>{$this->name()}</a>";
		}

		return parent::render();
	}
}
