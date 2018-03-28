<?php

namespace App;

use Redis;

class Visits
{

    protected $thread;

    /**
     * Visits constructor.
     * @param $thread
     */
    public function __construct ($thread)
    {

        $this->thread = $thread;
    }

    public function reset ()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    protected function cacheKey ()
    {
        return "threads.{$this->thread->id}.visits";
    }

    public function record ()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    /**
     * @return bool|int|string
     */
    public function count ()
    {
        return Redis::get($this->cacheKey()) ?? 0;

    }


}