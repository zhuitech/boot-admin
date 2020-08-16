<?php
/**
 * Created by PhpStorm.
 * User: breeze
 * Date: 2019-04-26
 * Time: 13:30
 */

namespace ZhuiTech\BootAdmin\Admin\Grid\Actions;

use Encore\Admin\Actions\RowAction;

/**
 * 链接
 *
 * Class Link
 * @package ZhuiTech\BootAdmin\Admin\Grid\Actions
 */
class Link extends RowAction
{
	private $link;

	public function __construct($link, $name = '跳转')
	{
		$this->link = $link;
		$this->name = $name;

		parent::__construct();
	}

	public function href()
	{
		return $this->link;
	}
}