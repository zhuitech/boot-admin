<?php
/**
 * Created by PhpStorm.
 * User: breeze
 * Date: 2019-04-26
 * Time: 13:30
 */

namespace ZhuiTech\BootAdmin\Admin\Grid\Actions;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

/**
 * 链接
 *
 * Class Link
 * @package ZhuiTech\BootAdmin\Admin\Grid\Actions
 */
class PopupEdit extends RowAction
{
    protected $attributes = [
        "data-toggle" => "modal",
        "data-target" => "#modal",
        "data-backdrop" => "static",
        "data-keyboard" => "false"
    ];

    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('admin.edit');
    }

    public function render()
    {
        $this->attribute('data-url', "{$this->getResource()}/{$this->getKey()}/edit");
        return parent::render();
    }

    public function handle(Model $model)
    {
        return $this->response()->success();
    }
}
