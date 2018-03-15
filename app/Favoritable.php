<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable () 
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete(); // for mor details see Records Activity Trait
        });
    }

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
    
    public function unfavorite()
    {
        $user_id = ['user_id' => auth()->id()];

        // NOTE: This I Like Model Scope And The Activity Delete Will ork See Record Activity Trait
        // same As the following line
        $this->favorites()->where($user_id)->get()->each->delete();
        
        // $this->favorites()->where($user_id)->get()->each(function ($favorite) {
        //     $favorite->delete();
        // });

        
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    // custom attribute and uses like isFavorited go to Reply.php and see append property
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    // custom attribute and uses like favoritesCount go to Reply.php and see append property
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
