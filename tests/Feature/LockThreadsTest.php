<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads ()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', [ 'user_id' => auth()->id() ]);

        $this->post(route('threads.lock.store', $thread))->assertStatus(403);

        $this->assertFalse(! ! $thread->fresh()->locked);
    }

    /** @test */
    public function an_administrators_can_lock_threads ()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', [ 'user_id' => auth()->id() ]);

        $this->post(route('threads.lock.store', $thread));

        $this->assertTrue(! ! $thread->fresh()->locked, 'Failed Asserting That The Thread Was Locked');

    }

    /** @test */
    public function once_locked_a_threaad_may_not_receive_a_replies ()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body'    => 'some body',
            'user_id' => auth()->id(),
        ])->assertStatus(422);
    }

}
