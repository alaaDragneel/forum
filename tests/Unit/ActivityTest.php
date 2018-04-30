<?php

namespace Tests\Unit;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_records_activity_when_a_thread_is_created ()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type'         => 'created_thread',
            'user_id'      => auth()->id(),
            'subject_id'   => $thread->id,
            'subject_type' => get_class($thread),
        ]);
        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created ()
    {
        $this->signIn();
        create('App\Reply');

        $this->assertEquals(3, Activity::count());
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user ()
    {
        $this->signIn();
        create('App\Thread', [ 'user_id' => auth()->id() ], 2);

        // reformat the created at because the activity use the now() method in timestamps
        auth()->user()->activities()->first()->update([ 'created_at' => Carbon::now()->subWeek() ]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(Carbon::now()->format('Y-m-d')));
        $this->assertTrue($feed->keys()->contains(Carbon::now()->subWeek()->format('Y-m-d')));

    }

}
