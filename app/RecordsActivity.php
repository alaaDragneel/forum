<?php

namespace App;


trait RecordsActivity
{

    protected static function bootRecordsActivity ()
    {
        if (auth()->guest()) return; // To Avoid Test Errors

        // all this lines work on model scope loke Favorite()->delete() see how we fix it in unfavorite method in favoritable trait
        foreach ( static::getActivitiesToRecords() as $event ) {
            static::$event(function ($model) use ($event)
            {
                $model->recordActivity($event);
            });
        }
        static::deleting(function ($model) {
            $model->activities()->delete();
        });
    }

    protected static function getActivitiesToRecords ()
    {
        return [ 'created', 'updated' ];
    }

    protected function recordActivity ($event)
    {
        $this->activities()->create([
            'type'    => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    public function activities ()
    {
        return $this->morphMany('App\Activity', 'subject');
    }


    protected function getActivityType ($event)
    {
        $type = strtolower(( new \ReflectionClass($this) )->getShortName()); // Get The Short Name App\Foo\Bar => Bar => bar

        return "{$event}_{$type}";
    }
}