<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

    /**
     * Get The Route key Name For Laravel
     * @return string
     */
    public function getRouteKeyName ()
    {
        return 'slug';
    }


    public function threads()
    {
        return $this->hasMany(Thread::class, 'channel_id');
    }

}
