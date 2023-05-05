<?php

namespace Wxlogin\Controllers\Web;


use Login\Resources\BaseResource;
use Login\Resources\ErrorResource;
use Illuminate\Http\Request;
use WxLogin\Repositories\Contracts\UnionInterface;
use WxLogin\Services\OfficialNo\OfficialNoLogin;
use DB;

/**
 * 公众号登录
 */
class OfficialController
{
    private $officialNoLogin;
    private $unionInterface;
    public function __construct(UnionInterface $unionInterface)
    {
        $this->officialNoLogin = new OfficialNoLogin(config('wx.official_app_id'),config('wx.official_secret'));
        $this->unionInterface = $unionInterface;
    }

    /**
     * 公众号授权登录
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(){

        return redirect($this->officialNoLogin->getCodeUrl(config('wx.redirect_url')));
    }

    /**
     * 微信授权并绑定用户
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function auth(Request $request)
    {

        $res = $this->officialNoLogin->getToken($request->code);
        if ($res && !empty($res['openid'])) {

            $wxInfo = $this->officialNoLogin->getUserInfo($res['openid'],$res['access_token']);

            if ($wxInfo && !empty($wxInfo['openid'])) {

                $res = $this->unionInterface->create($wxInfo);

               if ($res) {

                   return new BaseResource([]);
               }
            }
        }

        return new ErrorResource([]);
    }

}