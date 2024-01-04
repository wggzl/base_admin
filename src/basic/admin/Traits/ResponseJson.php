<?php
namespace Basic\Admin\Traits;
use Basic\Admin\Facade\Response;

trait ResponseJson
{
    //返回成功json
    protected function success(
        $message = null,
        $data= null,
        $header = [],
        $code = 0
    )
    {
        return Response::json(true, $code, $message, $data, $header);
    }

    /**
     * 返回错误 json
     */
    protected function error(
        $message = null,
        $code = 1,
        $data = [],
        $header = []
    ) {
        return Response::json(false, $code, $message, $data, $header);
    }

}
