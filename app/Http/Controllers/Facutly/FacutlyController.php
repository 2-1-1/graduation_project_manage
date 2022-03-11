<?php

namespace App\Http\Controllers\Facutly;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacutlyController extends Controller
{
    public function FacutlyListApi(Request $request)
    {
        $param = $request->post();
        $list = DB::table('facultylist')->get();

        $json['code'] = 200;
        $json['data'] = $list;

        return $json;
    }
}
