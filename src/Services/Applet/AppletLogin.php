<?php

namespace WxLogin\Services\Applet;

use Curl\CurlService\CurlService;

class AppletLogin extends AccessToken
{


    /**
     * 授权得到openid
     * @param $code
     * @return mixed
     */
    public function auth($code){
        try {

            $url = 'https://api.weixin.qq.com/sns/jscode2session';
            $data = [
                'appid' => $this->appId,
                'secret' => $this->secret,
                'js_code' => $code,
                'grant_type' => 'authorization_code',
            ];

            $res = CurlService::post($url,$data);
            if (empty($res['openid'])) {

                throw new \Exception(json_encode($res));
            }
            return $res;
        } catch (\Exception $exception) {

            throw new \Exception($exception->getMessage());
        }

    }

    /**
     * 获取手机号
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public function phone($code){

        try {
            $url = 'https://api.weixin.qq.com/wxa/business/getuserphonenumber';
            $accessToken = $this->accessToken();
            $data = [
                'access_token' => $accessToken['access_token'],
                'code' => $code
            ];

            $res =  CurlService::get($url,$data);
            if ($res['errcode'] != 0) {
                throw new \Exception(json_encode($res));
            }
            return $res;
        } catch (\Exception $exception) {

            throw new \Exception($exception->getMessage());
        }


    }

    /**
     * 同时获取授权信息和手机号
     * @param $code
     * @param $phoneCode
     * @return void
     * @throws \Exception
     */
    public function authAndPhone($code,$phoneCode){

        $authInfo = $this->auth($code);
        if ($authInfo) {

            $phoneInfo = $this->phone($phoneCode);
            if ($phoneInfo) {

                $authInfo['phone'] = $phoneInfo['phone_info']['phoneNumber'];
            }

            return $authInfo;
        }

        return null;
    }
}