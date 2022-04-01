<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\GetListController;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskDetail;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // var $user = Auth::user();

    public function objectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    public function getlistApi(Request $request)
    {
        $user = Auth::user();
        // global $user;
        global $param;
        $param = $request->post();
        $list = Task::where([
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
                    if (isset($param['title'])) {
                        $query->where(
                            'title',
                            'LIKE',
                            '%' . $param['title'] . '%'
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
        $list = TaskDetail::where([
            'faculty_id' => $user['faculty'],
        ])
            ->where([
                'task_id' => $param['id'],
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
        $path = $request->file('file')->store('public/task');

        $json['code'] = 200;
        $json['data'] = $path;
        return $json;
    }


    public function releaseTaskApi(Request $request)
    {
        global $param;
        $user = Auth::user();
        // global $user;
        $param = $request->post();
        Task::create([
            'number' => date('Ymdhis', time()) . str_random(6),
            'uid' => $param['file']['uid'],
            'name' => $param['file']['name'],
            'url' => $param['file']['url'],
            'title' => $param['title'],
            'status' => '0',
            'created_time' => date('Y-m-d h:i:s', time()),
            'teacher_id' => $user['id'],
            'teacher_name' => $user['name'],
            'faculty_id' => $user['faculty'],
        ]);

        $json['code'] = 200;
        $json['message'] = '发布任务成功';
        return $json;
    }

    public function approvalTaskApi(Request $request)
    {
        global $param;
        $param = $request->post();
        $user = Auth::user();
        if (isset($param['id'])) {
            $approvalObj = TaskDetail::where([
                'id' => $param['id'],
            ])->first();

            if (isset($param['event'])) {
                if ($approvalObj['status'] !== 'pass') {
                    if(isset($param['event'])){
                        TaskDetail::where([
                            'id' => $param['id'],
                        ])->first()->update([
                            'status' => $param['event']
                        ]);
                    }
                    if(isset($param['reason'])){
                        TaskDetail::where([
                            'id' => $param['id'],
                        ])->first()->update([
                            'remark' => $param['reason']
                        ]);
                    }

                    if ($param['event'] === 'pass') {
                        $taskDetailTotal = count(GetListController::getStudentListApi()['data']);
                        $taskDetailFinish = TaskDetail::where([
                            'task_id' => $param['task_id'],
                        ])
                            ->where([
                                'status' => 'pass',
                            ])
                            ->get()->count('id');

                        Task::where([
                            'id' => $param['task_id'],
                        ])->first()->update(['status' => round($taskDetailFinish / $taskDetailTotal, 2) * 100]);
                    }
                } else {
                    $json['code'] = 500;
                    $json['message'] = '已经完成的审批，请尝试刷新页面';
                    return $json;
                }
            }
        } else {
            TaskDetail::create([
                'status' => 'pendding',
                'task_id' => $param['task_id'],
                'uid' => $param['file']['uid'],
                'name' => $param['file']['name'],
                'url' => $param['file']['url'],
                'title' => $param['title'],
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
