<?php

namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * Json格式
 * 
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class Json extends AbstractDisplayer
{
    /**
     * Display method.
     *
     * @param array $option
     * @return mixed
     */
    public function display()
    {
        return self::render($this->value);
    }

    public static function render($value)
    {
        return '<pre><code>'.json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'</code></pre>';
    }
}