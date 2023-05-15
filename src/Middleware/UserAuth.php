<?php


namespace WxLogin\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class UserAuth
{
    public function handle($request, Closure $next)
    {

        //ticket参数出入
        $ticket = $request->ticket ? :$request->header('ticket');
        if ($ticket) {
            $userInfo = Cache::get($ticket);
            $userInfo['is_api'] = 1;
        } else{

            $userInfo = Session::get('ticket');
        }

        if (!$userInfo) {

            tne('NO_AUTH');
        }

        $request->merge(['ticket'=>$userInfo]);

        return $next($request);
    }
}