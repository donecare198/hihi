<?php

namespace App;

use Moloquent;

class UserAgent extends Moloquent
{
    protected $collection = 'useragent';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'sudung','lock'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public $timestamps = false;
}
