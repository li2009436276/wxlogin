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
     * 微信登录
     * @param $wxInfo
     * @param $type
     * @return mixed
     */
    public function login($wxInfo,$type);
}