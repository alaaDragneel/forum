<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Psy\CodeCleaner\AssignThisVariablePass;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];
    protected $with = [ 'owner', 'channel' ];

    protected static function boot ()
    {
        parent::boot();
        // no need for the next line becuase we add replies_count manually
        // static::addGlobalScope('replyCount', function ($builder)
        // {
        //     $builder->withCount('replies');
        // });

        static::deleting(function ($thread)
        {
            // TODO: NOTE Error With Sqlite error code 25 index columns excited run it with mysql and will work the error from php and sqlite
            if ( app()->environment() !== 'testing' )
                $thread->replies->each->delete();
                /*
                 * the line above equal the next line
                    $thread->replies->each(function ($reply) {
                        $reply->delete();
                    });
                 */
        });

    }

    public function path ()
    {
        return url("/threads/{$this->channel->slug}/{$this->id}");
    }

    public function owner ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel ()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function addReply ($reply)
    {
        return $this->replies()->create($reply);
    }

    public function replies ()
    {
        return $this->hasMany(Reply::class, 'thread_id');
    }

    public function scopeFilter ($query, $filters)
    {
        return $filters->apply($query);
    }

}
