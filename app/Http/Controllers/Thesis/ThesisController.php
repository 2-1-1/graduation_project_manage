<?php

namespace App\Http\Controllers\Thesis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thesis;
use App\Models\ThesisApproval;
use Illuminate\Support\Facades\Auth;

class ThesisController extends Controller
{
    public function objectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    public function getlistApi(Request $request)
    {
        global $param;
        $param = $request->post();
        $list = Thesis::where(
            function ($query) use ($param) {
                if (isset($param['startTime'])) {
                    $query->whereDate(
                        'created_time', '>', $param['startTime']
                    );
                }
            }
        )
        -> where(
            function ($query) use ($param) {
                if (isset($param['endTime'])) {
                    $query->whereDate(
                        'created_time', '<', $param['endTime']
                    );
                }
            }
        )
        -> where(
            function ($query) use ($param) {
                if (isset($param['status'])) {
                    $query->where([
                        'status' => $param['status']
                    ]);
                }
            }
        )
        -> where(
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
        $param = $request->post();
        $data = Thesis::where([
            'id' => $param['id'],
        ])->first();
        $data['approvalList'] = ThesisApproval::where([
            'thesis_id' => $param['id'],
        ])->get();

        $json['code'] = 200;
        $json['data'] = $data;
        return $json;
    }

    public function approvalThesisApi(Request $request)
    {
        global $param;
        $param = $request->post();

        $approvalList = ThesisApproval::where([
            'thesis_id' => $param['id'],
        ])->get();
        $thesisObj = Thesis::where([
            'id' => $param['id'],
        ])->first();
        $approvalList = array_filter(self::objectToArray($approvalList), function ($Arr) {
            global $param;
            return $Arr['event'] === $param['event'];
        });

        if (isset($param['event']) && $thesisObj['status'] !== 'pass' && $thesisObj['status'] !== 'reject') {
            $user = Auth::user();
            switch ($param['event']) {
                case 'pass':
                    if (!array_search("pass", array_column($approvalList, 'event'))) {
                        ThesisApproval::create([
                            'event' => $param['event'],
                            'thesis_id' => $param['id'],
                            'title' => $param['event'] === 'pass' ? '通过了审批' : ($param['event'] === 'reject' ? '拒绝了审批' : '发起了提交'),
                            'description' => $param['event'] === 'pass' 
                            ? $user['name'] . '在' . date('Y-m-d h:i:s', time()) . '通过了审批' 
                            : ($param['event'] === 'reject' 
                            ? $user['name'] . '在' . date('Y-m-d h:i:s', time()) . '拒绝了审批' 
                            : $user['name'] . '在' . date('Y-m-d h:i:s', time()) . '发起了提交'),
                        ]);
                        Thesis::where([
                            'id' => $param['id'],
                        ])->first()->update(['status' => $param['event'], 'grade' => $param['grade']]);
                    } else {
                        $json['code'] = 500;
                        $json['message'] = '已经通过的审批，请勿重复提交';
                        return $json;
                    }
                    break;
                case 'reject':
                    if (!array_search("reject", array_column($approvalList, 'event'))) {
                        ThesisApproval::create([
                            'event' => $param['event'],
                            'thesis_id' => $param['id'],
                            'title' => '拒绝了审批',
                            'description' => $user['name'] . '在' . date('Y-m-d h:i:s', time()) . '拒绝了审批',
                        ]);
                        Thesis::where([
                            'id' => $param['id'],
                        ])->first()->update(['status' => $param['event']]);
                    } else {
                        $json['code'] = 500;
                        $json['message'] = '已经拒绝的审批，请勿重复提交';
                        return $json;
                    }
                    break;
                case 'pendding':
                    if (!array_search("pendding", array_column($approvalList, 'event'))) {
                        ThesisApproval::create([
                            'event' => $param['event'],
                            'thesis_id' => $param['id'],
                            'title' => '发起了提交',
                            'description' => $user['name'] . '在' . date('Y-m-d h:i:s', time()) . '发起了提交',
                        ]);
                        Thesis::where([
                            'id' => $param['id'],
                        ])->first()->update(['status' => $param['event']]);
                    } else {
                        $json['code'] = 500;
                        $json['message'] = '已经发起的提交，请勿重复提交';
                        return $json;
                    }
                    break;
                default:
                    $json['code'] = 500;
                    $json['message'] = '非法提交';
                    return $json;
                    break;
            }
        } else {
            $json['code'] = 500;
            $json['message'] = '已经完成的审批，请尝试刷新页面';
            return $json;
        }

        $json['code'] = 200;
        $json['message'] = '操作成功';
        return $json;
    }
}
