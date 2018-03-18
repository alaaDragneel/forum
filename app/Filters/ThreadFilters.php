<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];
    /**
     * Filter the query by a given username
     * @param $username
     * @return mixed
     * @internal param $builder
     */
    protected function by ($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according to popular threads
     * @return $this
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = []; // clean the orders property to apply the new order we have because we set default order ith latest() in index of the controller
        return $this->builder->orderBy('replies_count', 'DESC');
    }

    /**
     * Filter the query according to Unanswered threads
     * @return $this
     */
    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}