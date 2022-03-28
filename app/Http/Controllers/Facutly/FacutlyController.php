<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function facultyListApi(Request $request)
    {
        $param = $request->post();
        $list = DB::table('facultylist')->get();

        $json['code'] = 200;
        $json['data'] = $list;

        return $json;
    }
}
