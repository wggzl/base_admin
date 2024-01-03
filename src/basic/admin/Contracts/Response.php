<?php

namespace Basic\Admin\Contracts;

interface Response
{
    /**
     * 响应json输出
     */
    public function json($success, $code, $message, $data);
}
