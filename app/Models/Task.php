<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
 /**
     * 关联到模型的数据表
     * @var string
     */
    protected $table = 'task';
    /**
     * Laravel有默认时间字段，如果不需要则去除
     * 表明模型是否应该被打上时间戳
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['number', 'title', 'status', 'uid', 'name', 'url', 'created_time', 'teacher_id', 'teacher_name', 'faculty_id'];
}
