<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GetListController extends Controller
{
    static public function getStudentListApi()
    {
        $user = Auth::user();
        $list = User::where([
            'faculty'=> $user['faculty'],
        ])
        -> where([
            'type'=> 'student',
        ])
        -> get();
        $json['code'] = 200;
        $json['data'] = $list;
        return $json;
    }
}
