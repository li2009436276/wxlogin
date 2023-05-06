<?php

namespace WxLogin\Repositories\Eloquent;

use MakeRep\Repository;
use WxLogin\Models\Union;
use WxLogin\Repositories\Contracts\UnionInterface;
use DB;

class UnionRepository extends Repository implements UnionInterface
{
    function model()
    {
        return Union::class;
    }

    /**
     * 创建微信信息
     * @param $wxInfo
     * @return mixed
     */
    public function create($wxInfo){

        DB::beginTransaction();

        //此处创建用户
        if (config('wx.is_bind_user')) {

            $userInfo = resolve(config('wx.bind_repository_class'))->bindWx($wxInfo);
            if (!$userInfo) {

                DB::rollback();

                return false;
            }
            $wxInfo['user_id'] = $userInfo['id'];
        }

        $wxInfo['privilege'] = json_encode($wxInfo['privilege']);

        $res = $this->add($wxInfo);
        if ($res) {

            DB::commit();
            return $res;
        }

        DB::rollback();
        return false;

    }
}