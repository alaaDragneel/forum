<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ReplyTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner ()
    {
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published ()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body ()
    {
        $reply = create('App\Reply', [
            'body' => 'Hallo @sasuke Look At This Man @moaalaa.',
        ]);

        $this->assertEquals([ 'sasuke', 'moaalaa' ], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags ()
    {
        $reply = create('App\Reply', [
            'body' => 'Hallo @sasuke.',
        ]);

        $this->assertEquals(
            'Hallo <a href="' . asset('/profiles/sasuke') . '">@sasuke</a>.',
            $reply->body
        );
    }

    /** @test */
    public function it_knows_if_it_is_the_best_reply ()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update([ 'best_reply_id' => $reply->id ]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
