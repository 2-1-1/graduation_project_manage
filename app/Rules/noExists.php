<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class noExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        if (User::where($attribute, '=', $value)->exists()) {
            // 有记录
            return false;
         }else{
             return true;
         }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '已注册的用户';
    }
}
