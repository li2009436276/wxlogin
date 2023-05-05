<?php


namespace Curl\CurlService;


use function Composer\Autoload\includeFile;

class CurlService
{
    static $curl = null;
    static $returnType = 1; //1返回json格式，0返回原数据，
    static $headers = [];

    public static function getInstance(){

        if (!self::$curl) {

            self::$curl = curl_init();
        }
    }

    /**
     * curl请求
     * @param $name
     * @param $param  $param[0]是地址，$param[1]是数据，$param[2]是header，$param[3]是返回数据类型,$param[4]是模拟的cookie文件存放地址
     * @return mixed
     */
    static function __callStatic($name,$param){

        self::getInstance();

        self::$returnType = empty($param[3]) ? 1 : 0;

        curl_setopt(self::$curl, CURLOPT_URL, $param[0]);

        //https请求
        if(substr($param[0],0,5) == 'https'){
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt(self::$curl, CURLOPT_TIMEOUT, 10);
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt(self::$curl, CURLOPT_ENCODING, 'gzip,deflate');
        self::$headers = !empty($param[2]) ? $param[2] : ['Content-type:application/json','Accept:application/json'];

        curl_setopt(self::$curl,CURLOPT_HTTPHEADER,self::$headers);
        //curl_setopt(self::$curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0');
        //curl_setopt(self::$curl, CURLOPT_COOKIE, 'JSESSIONID=F81DA14D98B50E36F0057A5B0518D481.HUIBEServer15; Webtrends=b243387c.5b5ef8e9ac721; gdpr_state=open; _ga=GA1.2.1185675196.1607415379; _gid=GA1.2.1188411127.1607415379; declarehugdpr=agreeusecookie; ClientID=fdea2d1c6066351a3fd792645b7c27ce; _gat_TrueMetrics=1; _gat=1');
        curl_setopt(self::$curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        //添加模拟浏览器cookie
        if (!empty($param[4])) {

            curl_setopt(self::$curl, CURLOPT_COOKIEJAR, $param[4]);
            curl_setopt(self::$curl, CURLOPT_COOKIEFILE, $param[4]); //使用上面获取的cookies
        }

        switch (strtolower($name)) {
            case 'get': return self::getReq();
            case 'post': return self::postReq(!empty($param[1]) ? $param[1] : []);
        }

    }

    /**
     * get提交
     * @return mixed
     */
    static function getReq(){

       return self::finish();
    }

    /**
     * post提交
     * @param $data
     * @return mixed
     */
    static function postReq($data = []){

        if (in_array('content-type:application/x-www-form-urlencoded',self::$headers)) {

            $data = http_build_query($data);
        } else {

            $data = json_encode($data);
        }
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);
        return self::finish();
    }

    /**
     * 处理并消除curl
     * @return mixed
     */
    static function finish(){

        $output = curl_exec(self::$curl);

        if (self::$returnType) {

            $output = json_decode($output,true);
        }

        self::close();
        return $output;
    }

    /**
     * 关闭curl
     */
    static function close(){
        curl_close(self::$curl);
        self::$curl = null;
    }
}