<?php

namespace App;

use Moloquent;

class LogLikes extends Moloquent
{
    protected $collection = 'log_likes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fbid', 'actionid', 'type','updated_at','created_at'
    ]; 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public $timestamps = true;
}
