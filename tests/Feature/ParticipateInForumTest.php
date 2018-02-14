<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function unauthenticated_user_may_not_add_reply() 
    {
        $this->withExceptionHandling();
        $this->post('/threads/some-channel/1/replies', [])->assertRedirect('/login');
    }
    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads() 
    {
        // Given we have a authenticated user
        $this->signIn();

        // And an Existing thread
        $thread = create('App\Thread');
        
        // When User Adds a Reply to thread
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());

        // Then Thier Reply Should be Visible On The Page
        $this->get($thread->path())
            ->assertSee($reply->body);
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

    /** test NOTE add @ before test to run it because there is error in sqlite3 */
    public function authorized_users_can_delete_replies ()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

    }
}
