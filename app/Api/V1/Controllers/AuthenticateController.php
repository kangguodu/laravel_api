<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Validator;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate','refresh']]);
    }


    public function refresh(Request $request)
    {
        $credentials = $request->only([
            'token'
        ]);
        $refreshed = '';
        try {
            JWTAuth::setToken($credentials['token'])->invalidate();
            $refreshed = JWTAuth::refresh($credentials['token']);
            //$user = JWTAuth::setToken($refreshed)->toUser();
        } catch (TokenExpiredException $e) {
            return $this->responseError('token 已超时，不可刷新',1010001,401);
        } catch (JWTException $e) {
            return $this->responseError('token 刷新失败',1010001,401);
        }
        return compact('refreshed');
    }
}