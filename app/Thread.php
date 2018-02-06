<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Psy\CodeCleaner\AssignThisVariablePass;

class Thread extends Model
{
    protected $guarded = [];
    
    public function path()
    {
        return url('threads/'. $this->id);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'thread_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply) 
    {
        $this->replies()->create($reply);
    }
}
