<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subject ()
    {
        return $this->morphTo();
    }

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function feed ($user, $take = 50)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity)
            {
                return $activity->created_at->format('Y-m-d');
            });
    }

}
