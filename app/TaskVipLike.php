<?php

namespace App;

use Moloquent;

class TaskVipLike extends Moloquent
{
    protected $collection = 'task_viplike';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fbid', 'postid','hoanthanh','story','limit','goi','reaction','updated_at','created_at'
    ]; 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public $timestamps = false;
}
