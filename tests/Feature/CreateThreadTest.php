<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads ()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authentecated_user_can_create_new_threads ()
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


    /** @test */
    public function guests_cannot_delete_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = create('App\Thread');

        $this->delete($thread->path())
        ->assertRedirect('/login');

    }

    /** @test */
    public function a_thread_can_be_deleted ()
    {
        // TODO: NOTE Error With Sqlite error code 25 index columns excited run it with mysql and will work the error from php and sqlite
//        $this->signIn();
//
//        $thread = create('App\Thread');
//        $reply = create('App\Reply', [ 'thread_id' => $thread->id ]);
//        $response = $this->json('DELETE', $thread->path());
//
//        $response->assertStatus(204);
//
//        $this->assertDatabaseMissing('threads', [ 'id' => $thread->id ]);
//        $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]);
    }

    /** @test */
    public function threads_may_only_be_deleted_by_who_have_permissions ()
    {
        // TODO
    }
}
