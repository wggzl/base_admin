<?php
namespace Basic\Admin\Contracts;
interface Captcha
{
    /**
     * 设置配置
     *
     * @param   string|array    $name
     */
    public function withConfig($name, $value = null);
}
