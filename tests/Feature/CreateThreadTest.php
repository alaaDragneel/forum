<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads ()
    {
        $this->withExceptionHandling();
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')->assertRedirect('/login');
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
    public function unauthorized_users_cannot_delete_threads()
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
    public function authorized_users_can_delete_threads()
    {
        // TODO: NOTE Error With Sqlite error code 25 index columns excited run it with mysql and will work the error from php and sqlite
        $this->signIn();


        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', [ 'thread_id' => $thread->id ]);
        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [ 'id' => $thread->id ]);
//         $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]); // Leave it before testing error with the sqlite3
//        $this->assertEquals(0, Activity::count()); // uncomment when uncomment the reply

    }
}
