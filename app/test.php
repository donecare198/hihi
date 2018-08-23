<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Moloquent;

class test extends Moloquent implements Authenticatable{
    protected $collection = 'User';
    protected $fillable = ['name', 'email', 'password',];
    public $timestamps = true;

}