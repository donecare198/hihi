<?php

namespace App;

use Moloquent;

class TokenLikes extends Moloquent
{
    protected $collection = 'token_likes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fbid','name','access_token','gender','locale','live','updated_at','created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public $timestamps = true;
}
