<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function guests_cannot_favorite_any_things ()
    {
        $this->withExceptionHandling();
        $this->post('replies/1/favorites')->assertRedirect('/login');

    }

    /** @test */
    public function an_authinticated_user_can_favorite_any_reply ()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authinticated_user_can_unfavorite_reply ()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $reply->favorite();

        $this->delete('replies/' . $reply->id . '/favorites');
        $this->assertCount(0, $reply->favorites);
        // $reply->fresh() Give You A Fresh Instance Of The Reply Like New One To CHeck The Favorite
        // $this->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    public function an_authinticated_user_may_only_favorite_reply_once ()
    {
        $this->signIn();
        $reply = create('App\Reply');
        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch ( \Exception $e ) {
            $this->fail('Did Not Expect To Insert The Same Record set Twice');
        }
        $this->assertCount(1, $reply->favorites);
    }
}
