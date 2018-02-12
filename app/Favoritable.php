<?php

namespace App;

trait Favoritable
{
    public function favorites()
    {
        // note the sond arg is favorited_id but the function need it ith out _id o we put it like this favorited
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $user_id = ['user_id' => auth()->id()];
        $favoriteExists = $this->favorites()->where($user_id)->exists();
        if (!$favoriteExists) {
            return $this->favorites()->create($user_id);
        }
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }
}
