<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    /**
     * Get The Route key Name For Laravel
     * @return string
     */
    public function getRouteKeyName ()
    {
        return 'name';
    }

    public function threads ()
    {
        return $this->hasMany(Thread::class, 'user_id')->latest();
    }

    public function lastReply ()
    {
        return $this->hasOne(Reply::class)->latest();
    }


    public function activities ()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function confirmed ()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    public function isAdmin ()
    {
        return $this->type === 'admin';
    }


    public function read ($thread)
    {
        // Simulate that The User Visited The Thread
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            now()
        );
    }

    public function visitedThreadCacheKey ($thread)
    {
        return sprintf("users.%s.visits,%s", $this->id, $thread->id);
    }

    public function getAvatarPathAttribute ($avatar)
    {
        return asset($avatar ? 'storage/' . $avatar : 'images/avatar/profile.png');
    }


}
