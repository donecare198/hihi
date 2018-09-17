<?php

namespace App;

use Moloquent;

class Viplike extends Moloquent
{
    protected $collection = 'viplike';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fbid', 'thoigian','limit','goi','thongbao','reaction','active','fbid_notification','updated_at','fbname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public $timestamps = true;
}
