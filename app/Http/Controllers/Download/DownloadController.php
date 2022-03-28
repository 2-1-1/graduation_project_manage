<?php

namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadController extends Controller
{

    public function downloadApi(Request $request)
    {
        $param = $request->post();
        return response()->download(base_path().'\storage\\app\\'.$param['url']);
    }
}
