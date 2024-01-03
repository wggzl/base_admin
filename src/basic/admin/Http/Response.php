<?php

namespace Basic\Admin\Http;

use Basic\Admin\Contracts\Response as ResponseContract;
use Illuminate\Support\Traits\Macroable;

/**
 * 响应
 */
class Response implements ResponseContract
{
    use Macroable;

    //跨域
    protected $isAllowOrigin = false;
    //允许跨域域名
    protected $allowOrigin;
    //是否允许后续请求携带认证信息 （cookie）
    protected $allowCredentials = false;
    //该次请求的请求方式
    protected $allowMethods = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
    // 预检结果缓存时间,缓存
    protected $maxAge = '';

    // 该次请求的自定义请求头字段
    protected $allowHeaders = 'X-Requested-With,X_Requested_With,Content-Type';

    // js 允许获取的 header 字段
    protected $exposeHeaders = 'Authorization,authenticated';



    //是否允许跨域
    public function withIsAllowOrigin($isAllowOrigin = false)
    {
        $this->isAllowOrigin = $isAllowOrigin;
        return $this;
    }

    //允许跨域域名
    public function withAllowOrigin($allowOrigin = '*')
    {
        $this->allowOrigin = $allowOrigin;
        return $this;
    }

    //允许后续请求携带认证信息
    public function withAllowCredentials($allowCredentials = false)
    {
        $this->allowCredentials = $allowCredentials;
    }

    //该次请求的请求方式
    public function withAllowMethods($allowMethods = false)
    {
        $this->allowMethods = $allowMethods;

        return $this;
    }

    /**
     * 预检结果缓存时间
     */
    public function withMaxAge($maxAge = '')
    {
        $this->maxAge = $maxAge;
        return $this;
    }

    /**
     * 该次请求的自定义请求头字段
     */
    public function withAllowHeaders($allowHeaders = false)
    {
        $this->allowHeaders = $allowHeaders;

        return $this;
    }

    /**
     * 设置 js 允许获取的 header 字段
     */
    public function withExposeHeaders($exposeHeaders = false)
    {
        $this->exposeHeaders = $exposeHeaders;

        return $this;
    }


    public function json($success, $code, $message, $data)
    {

    }
}
