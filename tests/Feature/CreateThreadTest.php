<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function an_authentecated_user_can_create_new_threads()
    {
        // Given we have a signed in user
        $this->signIn();

        // when we hit the endpoint to create a new thread
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        // then when we visit the thread page
        $this->get($response->headers->get('Location'))

        // we should see the ne thread
        ->assertSee($thread->title)
        ->assertSee($thread->body);
    }
}
