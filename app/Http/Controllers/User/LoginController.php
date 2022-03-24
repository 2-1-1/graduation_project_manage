<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function loginApi(Login $request)
    {
        $param = $request->post();
        if (Auth::attempt(['phone' => $param['phone'], 'password' => $param['password']])) {
            // 认证通过...
            $json['code'] = 200;
            $obj['username'] = $param['phone'];
            $user = User::where([
                'phone'=> $param['phone'],
            ]) -> first();
            $obj['type'] = $user['type'];
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
