<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Psy\CodeCleaner\AssignThisVariablePass;

class Thread extends Model
{
    protected $guarded = [];
    protected $with = ['owner', 'channel'];
    protected static function boot ()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
    }

    public function path()
    {
        return url("/threads/{$this->channel->slug}/{$this->id}");
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function addReply($reply) 
    {
        $this->replies()->create($reply);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'thread_id');
    }

    public function scopeFilter ($query, $filters)
    {
        return $filters->apply($query);
    }

}
