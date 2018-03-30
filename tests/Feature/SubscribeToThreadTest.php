<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_user_can_subscribe_to_threads ()
    {
        $this->signIn();

        // Given We Have A Thread ...
        $thread = create('App\Thread');

        // And The User Subscribes To The Thread ...
        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads ()
    {
        $this->signIn();

        // Given We Have A Thread ...
        $thread = create('App\Thread');

        $thread->subscribe();
        // And The User Subscribes To The Thread ...
        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
