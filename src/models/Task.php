<?php

namespace Inextends\Tamandua\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;
    
    protected $table = 'task';
    protected $dates = ['deleted_at'];
    
    public function creator()
    {
        return $this->belongsTo('Inextends\Tamandua\Models\User', 'creator_id');
    }
    
    public function assignee()
    {
        return $this->belongsTo('Inextends\Tamandua\Models\User', 'assigned_to_id');
    }
    
    public function project()
    {
        return $this->belongsTo('Inextends\Tamandua\Models\Project');
    }
}
