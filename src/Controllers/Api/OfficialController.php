<?php

namespace WxLogin\Controllers\Api;


use Curl\TicketService\TicketService;
use WxLogin\Resources\BaseResource;
use WxLogin\Resources\ErrorResource;
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
     * 静默授权
     * @return mixed
     */
    public function silentAuth(){
        return redirect($this->officialNoLogin->getCodeUrl(config('wx.silent_redirect_url')));
    }

    /**
     * 微信授权并绑定用户
     * @param Request $request
     * @return BaseResource|ErrorResource
     */
    public function auth(Request $request)
    {

        $res = $this->officialNoLogin->getToken($request->code);
        if ($res && !empty($res['openid'])) {

            //绑定后开始登录
            $userInfo = $this->unionInterface->login($res);
            if (!$userInfo) {

                if (config('wx.official_auth_type') == 'snsapi_userinfo') {
                    $wxInfo = $this->officialNoLogin->getUserInfo($res['openid'], $res['access_token']);

                    if ($wxInfo && !empty($wxInfo['openid'])) {
                        $wxInfo['invite_no'] = $request->invite_no;
                        $res = $this->unionInterface->create($wxInfo);
                        if ($res) {

                            $userInfo = $this->unionInterface->login($res);
                        }
                    }
                } else {

                    $res = $this->unionInterface->create(['openid'=>$res['openid'],'invite_no'=>$request->invite_no]);
                    if ($res) {

                        $userInfo = $this->unionInterface->login($res);
                    }
                }
            }

            if ($userInfo) {

                //绑定后开始登录
                $ticket = TicketService::createTicket($userInfo);
                return new BaseResource(['ticket' => $ticket]);
            }


        }
        return new ErrorResource([]);
    }

}