<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_may_not_add_reply ()
    {
        $this->withExceptionHandling();
        $this->post('/threads/some-channel/1/replies', [])->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads ()
    {
        // Given we have a authenticated user
        $this->signIn();

        // And an Existing thread
        $thread = create('App\Thread');

        // When User Adds a Reply to thread
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', [ 'body' => $reply->body ]);
        // fresh() => will get fereh data from the database
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies ()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->delete("/replies/{$reply->id}")->assertRedirect('/login');

        $this->signIn();
        $this->delete("/replies/{$reply->id}")->assertStatus(403);
    }

    /** @test NOTE add @ before test to run it because there is error in sqlite3 */
    public function authorized_users_can_delete_replies ()
    {
        $this->signIn();
        $reply = create('App\Reply', [ 'user_id' => auth()->id() ]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies ()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->patch("/replies/{$reply->id}")->assertRedirect('/login');

        $this->signIn();
        $this->patch("/replies/{$reply->id}")->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies ()
    {
        $this->signIn();
        $reply = create('App\Reply', [ 'user_id' => auth()->id() ]);
        $updatedReply = 'You Been Changed, Fool.';
        $this->patch("/replies/{$reply->id}", [ 'body' => $updatedReply ]);
        $this->assertDatabaseHas('replies', [ 'id' => $reply->id, 'body' => $updatedReply ]);
    }

    /** @test */
    public function replies_that_contains_spam_may_not_be_created ()
    {
        $this->withExceptionHandling();

        // Given we have a authenticated user
        $this->signIn();

        // And an Existing thread
        $thread = create('App\Thread');

        // When User Adds a Reply to thread
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support',
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function users_may_only_reply_a_maximum_of_once_per_minute ()
    {
        $this->withExceptionHandling();

        // Given we have a authenticated user
        $this->signIn();

        // And an Existing thread
        $thread = create('App\Thread');

        // When User Adds a Reply to thread
        $reply = make('App\Reply', [
            'body' => 'My Simple Reply',
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(200);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);
    }
}
