<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function classListApi(Request $request)
    {
        $param = $request->post();
        $list = DB::table('class')
        ->where([
            'faculty_id'=> $param['facultyId'],
        ]) -> get();

        $json['code'] = 200;
        $json['data'] = $list;

        return $json;
    }
}
