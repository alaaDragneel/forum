<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];
    protected $with = [ 'owner', 'channel' ];
    protected $appends = [ 'isSubscribedTo' ];

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
            if ( app()->environment() !== 'testing' ) {
                $thread->replies->each->delete();
            }
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel ()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    /**
     * Add A New Thread
     * @param $reply
     * @return Model
     */
    public function addReply ($reply)
    {
        $newReply = $this->replies()->create($reply);

        // prepare Notifications For All Subscribers
        $this->subscriptions->filter(function ($sub) use ($newReply) {
            return $sub->user_id != $newReply->user_id;
        })
        ->each->notify($newReply);
//        ->each(function ($sub) use ($this, $newReply) {
//            $sub->notify(new ThreadWasUpdated($this, $newReply));
//        });


        return $newReply;
    }

    public function replies ()
    {
        return $this->hasMany(Reply::class, 'thread_id');
    }

    public function scopeFilter ($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe ($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }

    public function unsubscribe ($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions ()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute ()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

}
