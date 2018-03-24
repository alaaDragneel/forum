<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionsUsersTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified ()
    {
        // Given We Have User, alaa, Who is signed in
        $alaa = create('App\User', [ 'name' => 'alaa' ]);

        $this->signIn($alaa);

        // And We Have Another User Sasuke
        $sasuke = create('App\User', [ 'name' => 'sasuke' ]);

        // If We Have A Thread
        $thread = create('App\Thread');

        // And Alaa Replies Mentions Sasuke
        $reply = make('App\Reply', [
            'body' => 'Hallo @sasuke Look At This Man @moaalaa.',
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        // Then, Sasuke Should Be Notified
        $this->assertCount(1, $sasuke->notifications);
    }
}
