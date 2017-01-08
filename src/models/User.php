<?php

namespace Inextends\Tamandua\Models;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user';
    
    public function authToken()
    {
        return $this->hasOne('Inextends\Tamandua\Models\AuthToken');
    }
}
