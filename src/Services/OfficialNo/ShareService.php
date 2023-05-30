<?php

namespace WxLogin\Services\OfficialNo;

use Illuminate\Support\Facades\Cache;

class ShareService
{
    public static function getJsApiTicket()
    {
        $data =  Cache::get('jsapi_ticket');
        if (!$data) {
            $accessToken = self::getApiAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode(self::httpRequest($url));
            $ticket = $res->ticket;
            if ($ticket) {

                Cache::put('jsapi_ticket', $ticket,7000);
            }
        } else {
            $ticket = $data;
        }

        return $ticket;
    }


    /*微信公众号，不需要获取用户信息：所以不需要授权，即使用appid、appsecret和grant_type换取access_token*/
    /*
    微信对于access_token的请求存在日请求数的限制 所以要全局缓存access_token,在过期时间内直接使用存的值；
    这里使用file_put_contents()代替fwrite()、fopen()、fclose()；
     file_put_content()如果文件不存在就先创建文件这里要注意把缓存文件放/dev/shm/*下面，这个磁盘文件会在重启的时候清空数据，
    由于这个access_token丢失也不会存在问题，所以存在这里有助于减轻磁盘内存压力
    */

    public static function getApiAccessToken()
    {

        $token_file = 'D:/phpstudy_pro/WWW/access_token.json';
        //$token_file = 'D:/access_token.json';
        $data = Cache::get('share_access_token');
        if (!$data) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".\config('wx.official_app_id')."&secret=".\config('wx.official_secret');
            $res = json_decode(self::httpRequest($url));
            $access_token = $res->access_token;
            if ($access_token) {

                Cache::put('share_access_token',$access_token,7000);
            }
        } else {
            $access_token = $data;
        }
        return $access_token;
    }

    public static function share(){

        //开始签名算法了
        $dataa['noncestr'] = 'sjijfdddsif'; //随意字符串 一会要传到JS里去.要求一致

        $dataa['jsapi_ticket'] = self::getJsApiTicket();

        $dataa['timestamp'] = time();

        $dataa['url'] = 'http://'.$_SERVER['HTTP_HOST'].'/';//动态获取URL

        ksort($dataa);

        $signature = '';

        foreach($dataa as $k => $v){
            $signature .= $k.'='.$v.'&';

        }

        $signature = substr($signature, 0, strlen($signature)-1);
        $dataa['signature'] = sha1($signature);// 必填，签名，见附录1
        $dataa['appId'] = \config('wx.official_app_id');
        $dataa['nonceStr'] = $dataa['noncestr'];
        return $dataa;
    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    public static function httpRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        //file_put_contents('D:/weixin.' . date("Ymd") . '.log', date("Y-m-d H:i:s") . "\t" . $output . "\n", FILE_APPEND); //记录微信请求的相关日志，以用于排插错误
        return $output;
    }
}