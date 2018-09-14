<?php

namespace App;

use Moloquent;

class Token extends Moloquent
{
    protected $collection = 'tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fbid', 'access_token','name','gender','locale','useragent','updated_at','avatar','created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public $timestamps = false;
}
