<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
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
        return url("/threads/{$this->channel->slug}/{$this->slug}");
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

        event(new ThreadReceivedNewReply($newReply));

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

    public function subscriptions ()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function unsubscribe ($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function getIsSubscribedToAttribute ()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function hasUpdatesFor ($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function visits ()
    {
        return new Visits($this);
    }

    public function getRouteKeyName ()
    {
        return 'slug';
    }

    public function setSlugAttribute ($value)
    {
        if ( static::whereSlug($slug = str_slug($value))->exists() ) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug ($slug)
    {
        // NOTE In php 7 You Can Trait The String Like An Array
        $max = static::whereTitle($this->title)->latest('id')->value('slug');
        if ( is_numeric($max[ -1 ]) ) {
            return preg_replace_callback('/(\d+)$/', function ($matches)
            {
                return $matches[1] + 1;
            }, $max);


        }

        return "{$slug}-2";
    }

}
