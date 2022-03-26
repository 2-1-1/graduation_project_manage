<?php

namespace App\Http\Controllers\Facutly;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function ClassListApi(Request $request)
    {
        $param = $request->post();
        $list = DB::table('class')
        ->where([
            'facutly_id'=> $param['facutlyId'],
        ]) -> get();

        $json['code'] = 200;
        $json['data'] = $list;

        return $json;
    }
}
