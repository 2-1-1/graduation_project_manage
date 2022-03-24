<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisApproval extends Model
{
 /**
     * 关联到模型的数据表
     * @var string
     */
    protected $table = 'thesis_approval';
    /**
     * Laravel有默认时间字段，如果不需要则去除
     * 表明模型是否应该被打上时间戳
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['parent_id', 'title', 'description', 'event'];
}
