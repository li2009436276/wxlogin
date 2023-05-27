<?php

namespace WxLogin\Repositories\Contracts;

interface UnionInterface
{
    /**
     * 创建微信信息
     * @param $wxInfo
     * @return mixed
     */
    public function create($wxInfo);

    /**
     * 绑定手机号
     * @param $userId
     * @param $phone
     * @return mixed
     */
    public function bindPhone($userId,$phone);

    /**
     * 微信登录
     * @param $wxInfo
     * @param $type
     * @return mixed
     */
    public function login($wxInfo,$type = 1);
}