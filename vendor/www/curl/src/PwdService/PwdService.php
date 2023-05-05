<?php


namespace Curl\PwdService;


use Curl\StrService\StrService;

class PwdService
{

    /**
     * 生成密码
     * @param $pwd
     * @param false $isMd5  如果是true两次MD5=md5($salt.md5($pwd))
     * @return array
     */
    public static function makePwd($pwd,$isMd5 = false){

        if ($isMd5) {
            $pwd = md5($pwd);
        }

        $salt = StrService::randStr(5);
        $pwd  = md5($salt.$pwd);

        return ['salt'=>$salt,'pwd'=>$pwd];
    }

    /**
     * 校验密码
     * @param $pwd
     * @param $data
     * @return bool
     */
    public static function verifyPwd($pwd,$data){

        //判断密码
        if ($data['pwd'] != md5($data['salt'].$pwd)) {

            return false;
        }

        return true;
    }
}