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
        
        $this->post('/threads/1/replies', []);
    }
    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads() 
    {
        // Given we have a authenticated user
        $this->signIn($user = factory('App\User')->create());

        // And an Existing thread
        $thread = factory('App\Thread')->create();
        
        // When User Adds a Reply to thread
        $reply = factory('App\Reply')->make();
        $this->post('threads/' . $thread->id . '/replies', $reply->toArray()); 

        // Then Thier Reply Should be Visible On The Page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
