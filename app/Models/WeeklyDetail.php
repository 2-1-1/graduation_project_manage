<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyDetail extends Model
{
 /**
     * 关联到模型的数据表
     * @var string
     */
    protected $table = 'weekly_detail';
    /**
     * Laravel有默认时间字段，如果不需要则去除
     * 表明模型是否应该被打上时间戳
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['weekly_id', 'status', 'created_time', 'uid', 'name', 'url', 'faculty_id', 'student_id', 'student_name', 'remark'];

    static public function getDetailByStudent($faculty_id, $student_id, $weekly_id)
    {
        return self::where([
            'faculty_id' => $faculty_id,
        ])
            ->where(
                function ($query) use ($weekly_id) {
                    $query->where([
                        'weekly_id' => $weekly_id
                    ]);
                }
            )
            ->where(
                function ($query) use ($student_id) {
                    $query->where([
                        'student_id' => $student_id
                    ]);
                }
            )
            ->first();
    }
}
