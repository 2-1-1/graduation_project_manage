<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Register;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerApi(Register $request)
    {
        $param = $request->post();
        $user = User::create([
            'phone' => $param['phone'],
            'password' => Hash::make($param['password']),
            'faculty' => $param['faculty'],
            'class' => $param['class'],
            'name' => $param['name'],
            'sex' => $param['sex'],
            'type' => $param['type'],
        ]);
        $json['code'] = 200;
        $obj['username'] = $param['phone'];
        $json['data'] = $obj;
        $json['message'] = '注册成功';

        return $json;
    }
}
