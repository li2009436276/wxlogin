<?php

namespace WxLogin\Controllers\Api;

use WxLogin\Resources\BaseResource;
use WxLogin\Resources\ErrorResource;
use WxLogin\Services\OfficialNo\ShareService;
use Illuminate\Http\Request;

class ShareController
{

    /**
     * 分享
     * @return BaseResource|ErrorResource
     */
    public function share(Request $request){

        $data = ShareService::share($request->url);
        if ($data) {

            return new BaseResource($data);
        }

        return new ErrorResource([]);

    }
}