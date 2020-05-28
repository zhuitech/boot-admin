<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 大文件
 *
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class LargeFile extends AbstractDisplayer
{
    /**
     * Display method.
     *
     * @return mixed
     * @throws \Exception
     */
    public function display()
    {
        if ($this->value) {
            $src = storage_url(large_url($this->value));
            $name = basename($src);

            return <<<HTML
<a href='$src' download='{$name}' target='_blank' class='text-muted'>
    <i class="fa fa-download"></i> {$name}
</a>
HTML;
        }
    }
}