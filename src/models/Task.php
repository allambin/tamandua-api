<?php

namespace Inextends\Tamandua\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;
    
    protected $table = 'task';
    protected $dates = ['deleted_at'];
}
