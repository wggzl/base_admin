<?php

return [
    'captcha' => [

    ],
    'response'  => [
        'json' => [
            'is_allow_origin'   => env('BASIC_ADMIN_RESPONSE_JSON_IS_ALLOW_ORIGIN', 1),
            'allow_origin'      => env('BASIC_ADMIN_RESPONSE_JSON_ALLOW_ORIGIN', '*'),
            'allow_credentials' => env('BASIC_ADMIN_RESPONSE_JSON_ALLOW_CREDENTIALS', 0),
            'allow_methods'     => env('BASIC_ADMIN_RESPONSE_JSON_ALLOW_METHODS', 'GET,POST,PATCH,PUT,DELETE,OPTIONS'),
            // 发送数据到服务器可携带的请求头字段 [Access-Control-Allow-Headers]
            'allow_headers'     => env('BASIC_ADMIN_RESPONSE_JSON_ALLOW_HEADERS', 'X-Requested-With,X_Requested_With,Content-Type,Authorization,Locale-Language,Basic-Admin-Captcha-Id'),
            // 客户端可获取请求头字段 [Access-Control-Expose-Headers]
            'expose_headers'    => env('BASIC_ADMIN_RESPONSE_JSON_EXPOSE_HEADERS', ''),
            'max_age'           => env('BASIC_ADMIN_RESPONSE_JSON_MAX_AGE', ''),
        ]
    ],
];
