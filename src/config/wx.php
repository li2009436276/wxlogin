<?php

return [
    'api_prefix'        => 'api',                                    //路由前缀
    'official_app_id'   => env('WX_OFFICIAL_APP_ID'),
    'official_secret'   => env('WX_OFFICIAL_SECRET'),
    'redirect_url'      => env('REDIRECT_URI',env('APP_URL').'/official/auth'),
    'is_bind_user'      => true,
    'bind_repository_class' => resolve(''),
    'code' => [
        'success'   => [0,'成功'],
        'fail'      => [4001,'失败']
    ],
];