<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginApi(Login $request)
    {
        $param = $request->post();
        if (Auth::attempt(['phone' => $param['phone'], 'password' => $param['password']])) {
            // 认证通过...
            $json['code'] = 200;
            $obj['username'] = $param['phone'];
            $json['data'] = $obj;
            $json['message'] = '登录成功';
            return $json;
        }else{
            $json['code'] = 500;
            $json['message'] = '登录失败';
            return $json;
        }
    }
}
