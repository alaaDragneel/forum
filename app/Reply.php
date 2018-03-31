<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    use Favoritable, RecordsActivity;

    protected $guarded = [];
    protected $with = [ 'owner', 'favorites' ];
    // protected $withCount = ['favorites'];
    protected $appends = [ 'favoritesCount', 'isFavorited', 'isBest' ];

    public static function boot ()
    {
        parent::boot();

        static::created(function ($reply)
        {
            $reply->thread->increment('replies_count');
        });


        static::deleted(function ($reply)
        {
            $reply->thread->decrement('replies_count');
        });
    }


    public function owner ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread ()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function wasJustPublished ()
    {
        return $this->created_at->gt(now()->subMinute());
    }

    public function mentionedUsers ()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];

    }


    public function path ()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function setBodyAttribute ($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="' . asset('/profiles/$1') . '">$0</a>', $body);
    }

    public function isBest ()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute ()
    {
        return $this->isBest();
    }

    public function getBodyAttribute ($body)
    {
        return \Purify::clean($body);
    }

}
