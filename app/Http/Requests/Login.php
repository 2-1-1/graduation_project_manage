<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class Login extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone'=>'required',
            'password'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '账号不能为空',
            'password.required' => '密码不能为空',
            'password.min' => '密码不能少于8位',
        ];
    }
}
