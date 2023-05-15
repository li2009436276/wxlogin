<?php

namespace WxLogin\Services;

use Curl\CurlService\CurlService;

class AccessToken
{
    protected $appId;
    protected $secret;
    public function __construct($appId = null,$secret = null)
    {
        $this->appId = $appId ? : config('wx.applet_app_id');
        $this->secret = $secret ? : config('wx.applet_secret');
    }

    /**
     * 获取access_token
     * @return mixed
     * @throws \Exception
     */
    protected function accessToken(){

        try {
            $url = "https://api.weixin.qq.com/cgi-bin/token";
            $data = [
                'grant_type' => 'client_credential',
                'appid' => $this->appId,
                'secret' => $this->secret,
            ];

            $res = CurlService::get($url,$data);
            if (empty($res['access_token'])) {
                throw new \Exception(json_encode($res));
            }
            return  $res;
        } catch (\Exception $exception) {

            throw new \Exception("获取access_token报错:".$exception->getMessage());
        }

    }
}