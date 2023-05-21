<?php

namespace WxLogin\Controllers\Api;

use Curl\StrService\StrService;
use Curl\TicketService\TicketService;
use Illuminate\Support\Facades\Storage;
use WxLogin\Repositories\Contracts\UnionInterface;
use WxLogin\Resources\BaseResource;
use WxLogin\Resources\ErrorResource;
use WxLogin\Services\Applet\AppletLogin;
use Illuminate\Http\Request;
use WxLogin\Services\Avatar;

class AppletController
{
    protected $appletLogin;
    protected $unionInterface;
    public function __construct(UnionInterface $unionInterface)
    {

        $this->appletLogin = new AppletLogin();
        $this->unionInterface = $unionInterface;
    }

    /**
     * 获取授权信息
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function auth(Request $request){

        return $this->appletLogin->auth($request->code);
    }

    /**
     * 获取手机号
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function phone(Request $request) {

        return $this->appletLogin->phone($request->phoneCode);
    }

    /**
     * 同时获取授权和手机号
     * @param Request $request
     * @return mixed
     */
    public function authAndPhone(Request $request) {

        return $this->appletLogin->authAndPhone($request->code,$request->phoneCode);
    }

    /**
     * 小程序授权 并绑定用户
     * @param Request $request
     * @return void|BaseResource|ErrorResource
     * @throws \Exception
     */
    public function authBindUser(Request $request) {

        $authInfo = $this->appletLogin->auth($request->code);
        if ($authInfo) {

            $userInfo = $this->unionInterface->login($authInfo, 2);
            if ($userInfo) {

                $ticket = TicketService::createTicket($userInfo);
                return new BaseResource(['ticket' => $ticket]);

            } else {

                $data = [
                    'openid'    => $authInfo['openid'],
                    'nickname'  => $request->nickName,
                    'sex'       => $request->gender,
                    'country'   => $request->country,
                    'province'  => $request->province,
                    'city'      => $request->city,
                    'language'  => $request->language,
                    'type'      => 2,
                ];

                if (!empty($authInfo['unionid'])) {

                    $data['unionid'] = $authInfo['unionid'];
                }

                //保存base64图片
                $filePath = date('Y/m/d').StrService::randStr(16).'.png';
                $saveRes = Storage::put($filePath,base64_decode($request->avatarUrl));
                if ($saveRes) {

                    $data['headimgurl'] = $filePath;
                }

                //获取手机号
                if ($request->phoneCode) {

                    $phoneInfo = $this->appletLogin->phone($request->phoneCode);
                    if ($phoneInfo) {
                        $data['phone'] = $phoneInfo['phone_info']['phoneNumber'];
                    }
                }

                $res = $this->unionInterface->create($data);
                if ($res) {

                    //绑定后开始登录
                    $userInfo = $this->unionInterface->login($authInfo, 2);
                    if ($userInfo) {

                        $ticket = TicketService::createTicket($userInfo);
                        return new BaseResource(['ticket' => $ticket]);
                    }
                }

            }
            return new ErrorResource([]);
        }
    }

    /**
     * 小程序登录
     * @param Request $request
     * @return BaseResource|ErrorResource
     * @throws \Exception
     */
    public function login(Request $request){

        $authInfo = $this->appletLogin->auth($request->code);
        if ($authInfo) {

            $userInfo = $this->unionInterface->login($authInfo, 2);
            if ($userInfo) {

                $ticket = TicketService::createTicket($userInfo);
                return new BaseResource(['ticket' => $ticket]);
            }
        }



        return new ErrorResource([]);
    }
}