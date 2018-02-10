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
        $this->expectException('Illuminate\Auth\AuthenticationException');
        
        $this->post('/threads/some-channel/1/replies', []);
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
}
