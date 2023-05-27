<?php

return [
    'api_prefix'        => 'api',                                    //路由前缀
    'official_app_id'   => env('WX_OFFICIAL_APP_ID'),
    'official_secret'   => env('WX_OFFICIAL_SECRET'),
    'official_auth_type'=> env('WX_OFFICIAL_AUTH_TYPE','snsapi_userinfo'),
    'official_redirect_url'=> env('WX_OFFICIAL_REDIRECT_URI',env('APP_URL').'/official/auth'),

    'applet_app_id'   => env('WX_APPLET_APP_ID'),
    'applet_secret'   => env('WX_APPLET_SECRET'),
    'is_bind_user'      => true,
    'bind_repository_class' => '',
    'code' => [
        'success'   => [0,'成功'],
        'fail'      => [4001,'失败'],
        'no_login'  => [4002,'请登录'],
    ],
];