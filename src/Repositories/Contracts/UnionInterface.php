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
}