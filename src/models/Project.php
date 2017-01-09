<?php

namespace Inextends\Tamandua\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;
    
    protected $table = 'project';
    protected $dates = ['deleted_at'];
}
