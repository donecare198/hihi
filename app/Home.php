<?php

namespace App;

use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
class Home extends Authenticatable
{
    use Notifiable;

    /**
     * User wow-like
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = 'user';
    protected $fillable = [
        'fbid', 'name','money','roles','active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public $timestamps = true;
    protected $hidden = [
        'remember_token','updated_at'
    ];
}
