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

            //小程序和公众号都有授权情况判定
            if (!empty($wxInfo['unionid'])) {

                $unionRes = $this->index(['union_id'=>$wxInfo['unionid']]);
                if ($unionRes) {

                    $wxInfo['user_id'] = $unionRes['id'];
                }
            } else { //如果没有绑定则添加用户

                $userInfo = resolve(config('wx.bind_repository_class'))->bindWx($wxInfo);
                if (!$userInfo) {

                    DB::rollback();

                    return false;
                }
                $wxInfo['user_id'] = $userInfo['id'];
            }

        }

        unset($wxInfo['invite_no']);

        if ( !empty($wxInfo['privilege']) ) {
            $wxInfo['privilege'] = json_encode($wxInfo['privilege']);
        }

        $res = $this->add($wxInfo);
        if ($res) {

            DB::commit();
            return $res;
        }

        DB::rollback();
        return false;

    }

    /**
     * 绑定手机号
     * @param $userId
     * @param $phone
     * @return mixed|void
     */
    public function bindPhone($userId,$phone)
    {
        DB::beginTransaction();
        $res = $this->update(['user_id'=>$userId],['phone'=>$phone]);
        if ($res) {

            $userRes = resolve(config('wx.bind_repository_class'))->update(['id'=>$userId],['phone'=>$phone]);
            if ($userRes) {

                DB::commit();
                return true;
            }
        }
        DB::rollback();
        return false;
    }

    /**
     * 微信登录
     * @param $wxInfo
     * @Param $type
     * @return mixed
     */
    public function login($wxInfo,$type){

       $where  = [
           'openid' => $wxInfo['openid'],
           'type'   => $type
       ];

       $unionRes = $this->index($where);
       if ($unionRes) {

           return resolve(config('wx.bind_repository_class'))->findById($unionRes['user_id']);

       }

       return false;
    }
}