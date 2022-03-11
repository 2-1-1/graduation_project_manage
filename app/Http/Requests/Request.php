<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    //定义错误验证值只返回一个错误
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        //使用http异常处理类抛出异常
        throw new HttpResponseException(response()->json([
            'ret' => 422,
            'msg' => $validator->errors()->first(),
            'data' => []
        ], 200));
    }


}
