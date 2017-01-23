<?php

namespace Inextends\Tamandua\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;
    
    protected $table = 'project';
    protected $dates = ['deleted_at'];
    
    public function creator()
    {
        return $this->belongsTo('Inextends\Tamandua\Models\User', 'creator_id');
    }
    
    public function tasks()
    {
        return $this->hasMany('Inextends\Tamandua\Models\Task');
    }
}
