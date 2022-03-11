<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
 /**
     * 关联到模型的数据表
     * @var string
     */
    protected $table = 'user';
    /**
     * Laravel有默认时间字段，如果不需要则去除
     * 表明模型是否应该被打上时间戳
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['phone', 'password', 'faculty', 'class', 'name', 'sex', 'type', 'created_time', 'modified_time'];
}
