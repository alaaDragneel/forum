<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads ()
    {
        $this->withExceptionHandling();
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post(route('threads.index'))->assertRedirect('/login');
    }

    /** @test */
    public function new_users_must_confirm_their_email_address_before_creating_threads ()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->withExceptionHandling()->signIn($user);

        $thread = make('App\Thread');

        return $this->post(route('threads.index'), $thread->toArray())
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'You Must Confirm Your Email Address');
    }

    /** @test */
    public function a_user_can_create_new_threads ()
    {
        // Given we have a signed in user
        $this->signIn();

        // when we hit the endpoint to create a new thread
        $thread = make('App\Thread');
        $response = $this->post(route('threads.index'), $thread->toArray());

        // then when we visit the thread page
        $this->get($response->headers->get('Location'))
            // we should see the ne thread
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title ()
    {
        $this->publishThread([ 'title' => null ])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body ()
    {
        $this->publishThread([ 'body' => null ])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug ()
    {
        $this->signIn();

        $thread = create('App\Thread', [ 'title' => 'Foo Title', 'slug' => 'foo-title' ]);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads.store'), $thread->toArray());

        $this->assertTrue(Thread::where('slug', 'foo-title-2')->exists());

        $this->post(route('threads.store'), $thread->toArray());

        $this->assertTrue(Thread::where('slug', 'foo-title-3')->exists());
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug ()
    {
        $this->signIn();

        $thread = create('App\Thread', [ 'title' => 'Some Title 24', 'slug' => 'some-title-24' ]);

        $this->post(route('threads.store'), $thread->toArray());

        $this->assertTrue(Thread::where('slug', 'foo-title-24-2')->exists());

    }

    /** @test */
    public function unauthorized_users_cannot_delete_threads ()
    {
        // NOTE: Not Signed In
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $this->delete($thread->path())->assertRedirect('/login');

        // NOTE: signed In
        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);

    }

    /** @test */
    public function authorized_users_can_delete_threads ()
    {
        $this->signIn();


        $thread = create('App\Thread', [ 'user_id' => auth()->id() ]);
        $reply = create('App\Reply', [ 'thread_id' => $thread->id ]);
        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [ 'id' => $thread->id ]);
        $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]); // Leave it before testing error with the sqlite3
        $this->assertEquals(0, Activity::count()); // uncomment when uncomment the reply

    }


    protected function publishThread ($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post(route('threads.index'), $thread->toArray());

    }
}
