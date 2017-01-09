<?php

namespace Inextends\Tamandua\Models;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user';
    public $hidden = array('password', 'password_salt');
    
    public function authToken()
    {
        return $this->hasOne('Inextends\Tamandua\Models\AuthToken');
    }
}
