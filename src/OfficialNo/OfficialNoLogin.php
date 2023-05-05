<?php

namespace WxLogin\OfficialNo;

use Curl\CurlService\CurlService;

class OfficialNoLogin
{

    public $codeUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
    public $authTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";
    public $refreshTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';
    public $userUrl = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';
    public $code = '';

    public function __construct($appId, $secret)
    {
        $this->appId = $appId;
        $this->secret = $secret;
    }

    public function getCodeUrl($redirectUri){

        return sprintf($this->codeUrl,$this->appId, $redirectUri);
    }

    public function getToken($code)
    {
        $url = sprintf( $this->authTokenUrl, $this->appId, $this->secret, $code );
        $re = CurlService::post( $url );
        return $re;
    }

    public function resfrushToken($refresh_token) {

        $url = sprintf( $this->refreshTokenUrl, $this->appId, $refresh_token );
        $re = CurlService::get( $url );

        return $re;
    }

    public function getUserInfo($openid, $access_token)
    {
        $url = sprintf( $this->userUrl, $access_token, $openid );
        $re = CurlService::get( $url );
        return $re;
    }
}