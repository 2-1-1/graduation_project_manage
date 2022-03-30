<?php

namespace App\Http\Controllers\Weekly;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\GetListController;
use Illuminate\Http\Request;
use App\Models\Weekly;
use App\Models\WeeklyDetail;
use Illuminate\Support\Facades\Auth;

class WeeklyController extends Controller
{
    // var $user = Auth::user();

    public function objectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    static public function createWeeklyApi()
    {
        $list = GetListController::getTeacherListApi()['data'];
        for($i = 0 ;$i < count($list);$i++){
            echo $i;
            Weekly::create([
                'number' => date('Ymdhis', time()) . str_random(6),
                'weekly_date' => date('Y-m-d h:i:s', time()).'-'.date("Y-m-d H:i:s",strtotime("7 day")),
                'status' => '0',
                'created_time' => date('Y-m-d h:i:s', time()),
                'faculty_id' => $list[$i]['faculty'],
                'teacher_id' => $list[$i]['id'],
                'teacher_name' => $list[$i]['name'],
            ]);
        }
    }    

    public function getlistApi(Request $request)
    {
        $user = Auth::user();
        // global $user;
        global $param;
        $param = $request->post();
        $list = Weekly::where([
            'teacher_id' => $user['id'],
        ])
            ->where(
                function ($query) use ($param) {
                    if (isset($param['startTime'])) {
                        $query->whereDate(
                            'created_time',
                            '>',
                            $param['startTime']
                        );
                    }
                }
            )
            ->where(
                function ($query) use ($param) {
                    if (isset($param['endTime'])) {
                        $query->whereDate(
                            'created_time',
                            '<',
                            $param['endTime']
                        );
                    }
                }
            )
            ->where(
                function ($query) use ($param) {
                    if (isset($param['number'])) {
                        $query->where([
                            'number' => $param['number']
                        ]);
                    }
                }
            )
            ->get();

        $json['code'] = 200;
        $json['data'] = $list;
        return $json;
    }

    public function getdetailApi(Request $request)
    {
        global $param;
        $user = Auth::user();
        // global $user;
        $param = $request->post();
        $list = WeeklyDetail::where([
            'faculty_id' => $user['faculty'],
        ])
            ->where([
                'weekly_id' => $param['id'],
            ])
            ->where(
                function ($query) use ($param) {
                    if (isset($param['status'])) {
                        $query->where([
                            'status' => $param['status']
                        ]);
                    }
                }
            )
            ->where(
                function ($query) use ($param) {
                    if (isset($param['student_id'])) {
                        $query->where([
                            'student_id' => $param['student_id']
                        ]);
                    }
                }
            )
            ->get();

        $json['code'] = 200;
        $json['data'] = $list;
        return $json;
    }

    public function uploadApi(Request $request)
    {
        $path = $request->file('file')->store('public/weekly');

        $json['code'] = 200;
        $json['data'] = $path;
        return $json;
    }

    public function approvalWeeklyApi(Request $request)
    {
        global $param;
        $param = $request->post();
        $user = Auth::user();
        if (isset($param['id'])) {
            $approvalObj = WeeklyDetail::where([
                'id' => $param['id'],
            ])->first();

            if (isset($param['event'])) {
                if ($approvalObj['status'] !== 'pass') {
                    WeeklyDetail::where([
                        'id' => $param['id'],
                    ])->first()
                        ->where(
                            function ($query) use ($param) {
                                if (isset($param['event'])) {
                                    $query->update([
                                        'status' => $param['event']
                                    ]);
                                }
                                if (isset($param['reason'])) {
                                    $query->update([
                                        'remark' => $param['reason']
                                    ]);
                                }
                            }
                        );

                    if ($param['event'] === 'pass') {
                        $weeklyDetailTotal = count(GetListController::getStudentListApi()['data']);
                        $weeklyDetailFinish = WeeklyDetail::where([
                            'weekly_id' => $param['weekly_id'],
                        ])
                            ->where([
                                'status' => 'pass',
                            ])
                            ->get()->count('id');

                        Weekly::where([
                            'id' => $param['weekly_id'],
                        ])->first()->update(['status' => round($weeklyDetailFinish / $weeklyDetailTotal, 2) * 100]);
                    }
                } else {
                    $json['code'] = 500;
                    $json['message'] = '已经完成的审批，请尝试刷新页面';
                    return $json;
                }
            }
        } else {
            WeeklyDetail::create([
                'status' => 'pendding',
                'weekly_id' => $param['weekly_id'],
                'uid' => $param['file']['uid'],
                'name' => $param['file']['name'],
                'url' => $param['file']['url'],
                'created_time' => date('Y-m-d h:i:s', time()),
                'faculty_id' => $user['faculty'],
                'student_id' => $user['id'],
                'student_name' => $user['name'],
            ]);
        }

        $json['code'] = 200;
        $json['message'] = '操作成功';
        return $json;
    }
}
