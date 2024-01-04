<?php

namespace Basic\Admin\Http;

use Basic\Admin\Contracts\Response as ResponseContract;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Traits\Macroable;

/**
 * 响应
 */
class Response implements ResponseContract
{
    use Macroable;
    // 输出头信息列表
    protected $headers;

    //跨域
    protected $isAllowOrigin = false;
    //允许跨域域名
    protected $allowOrigin = '*';
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

    /**
     * 设置header
     */
    public function withHeader($name, $content = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->withHeader($key, $value);
            }
        } else {
            if (!empty($name)) $this->headers[$name] = $content;
        }
        return $this;
    }

    /**
     * 获取headers
     */
    public function getHeaders()
    {
        if ($this->isAllowOrigin) return $this->headers;
        return [];
    }

    public function mergeCorsHeaders()
    {
        $header['Access-Control-Allow-Origin']  = $this->allowOrigin;
        $header['Access-Control-Allow-Headers'] = $this->allowHeaders;
        $header['Access-Control-Expose-Headers'] = $this->exposeHeaders;
        $header['Access-Control-Allow-Methods'] = $this->allowMethods;
        /**
         * Access-Control-Allow-Credentials 是一个HTTP响应头，
         * 用于控制跨域请求中是否允许发送身份凭证（比如cookies、HTTP认证或客户端SSL证书）。
         * 当服务器收到一个带有Credentials标志的请求时，如果没有设置Access-Control-Allow-Credentials头，那么服务器将拒绝该请求。
         * 这个头的值可以是true或false。当值为true时，表示服务器允许发送身份凭证，而当值为false时，表示服务器不允许发送身份凭证。
         * 需要注意的是，如果设置了Access-Control-Allow-Origin头来控制跨域请求的来源，那么Access-Control-Allow-Credentials的值不能为*，
         * 必须是具体的域名。这是出于安全性的考虑，以防止恶意网站盗取用户的凭证信息。
         * 当开发者需要在跨域请求中发送身份凭证时，可以在服务器端设置Access-Control-Allow-Credentials头为true，
         * 并确保请求的来源（通过Access-Control-Allow-Origin头）是受信任的域名。这样浏览器就会允许在跨域请求中发送身份凭证，从而实现安全的跨域通信。
         */

        if ($this->allowCredentials === true) {
            $header['Access-Control-Allow-Credentials'] = "true";
        }

        /**
         * Access-Control-Max-Age 是一个HTTP响应头，用于指定对预检请求（Preflight Request）的结果进行缓存的时间，
         * 以减少对服务器的重复预检请求。预检请求是在发送实际的跨域请求之前，浏览器会发送一个 OPTIONS 请求，
         * 以询问服务器是否允许发送实际请求。这个预检请求包含一些跨域请求所需的信息，例如请求方法、请求头等。
         * Access-Control-Max-Age 头的值是一个以秒为单位的整数。当服务器返回预检请求的响应时，
         * 可以设置 Access-Control-Max-Age 头来指定预检请求的结果可以被缓存的时间长度。
         * 在此时间内，浏览器可以使用缓存的预检结果，而无需再次发送预检请求。
         * 通过设置 Access-Control-Max-Age 头，可以减少跨域请求的延迟和网络流量，提高性能。
         * 服务器可以根据实际需求设置合适的缓存时间，以平衡性能和安全性。
         * 需要注意的是，Access-Control-Max-Age 头只对预检请求有效，对于实际的跨域请求不起作用。
         * 实际的跨域请求每次发送时，仍然需要进行跨域检查和授权。预检请求的缓存仅适用于相同的请求参数和请求头的情况。
         * 如果请求的参数或请求头发生变化，浏览器将重新发送预检请求。
         */
        if (! empty($this->maxAge)) {
            $header['Access-Control-Max-Age'] = $this->maxAge;
        }

        $this->withHeader($header);

        return $this;

    }


    /**
     * 输出响应
     */
    public function json(
        $success = true,
        $code = ResponseCode::INVALID,
        $message = "",
        $data = [],
        $userHeader = []
    )
    {
        $result['success']  = $success;
        $result['code']    = $code;
        $message ? $result['message'] = $message : null;
        $data ? $result['data'] = $data : null;
        // 返回 JSON
        $this->returnJson($result, $userHeader);
    }

    /**
     * 将数组以标准 json 格式返回
     *
     * @param   array    $data
     * @param array $userHeader
     * @return  string   json
     */
    public function returnJson(array $data, array $userHeader = [])
    {
        $contents = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        $this->returnJsonFromString($contents, $userHeader);
    }

    /**
     * 将 json 字符串以标准 json 格式返回
     */
    public function returnJsonFromString($contents, $userHeader = [])
    {
        // 添加 json 输出相应
        $header = array_merge($userHeader, [
            'Content-Type' => 'application/json; charset=utf-8',
        ]);
        $this->returnData($contents, $header);
    }

    /**
     * 返回数据
     */
    public function returnData($contents, $userHeader = [])
    {
        $this->mergeCorsHeaders()->withHeader($userHeader);
        $header = $this->getHeaders();

        $response = response($contents, 200, $header);
        throw new HttpResponseException($response);  //复习一下laravel的异常处理
    }

    /**
     * 返回字符
     */
    public function returnString($contents, $userHeader = [])
    {
        // 文件输出相应
        $header = array_merge($userHeader, [
            'Content-Type' => 'text/html',
        ]);

        $this->returnData($contents, $header);
    }
}
