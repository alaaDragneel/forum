<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{

    use RefreshDatabase;

    public function setUp ()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads ()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread ()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel ()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', [ 'channel_id' => $channel->id ]);
        $threadNotInChannel = create('App\Thread');

        $response = $this->get('/threads/' . $channel->slug);
        $response->assertSee($threadInChannel->title);
        $response->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username ()
    {
        $this->signIn(create('App\User', [ 'name' => 'alaaDragneel' ]));

        $threadByAlaaDragneel = create('App\Thread', [ 'user_id' => auth()->id() ]);
        $threadNotByAlaaDragneel = create('App\Thread');

        $response = $this->get('/threads?by=alaaDragneel');
        $response->assertSee($threadByAlaaDragneel->title);
        $response->assertDontSee($threadNotByAlaaDragneel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity ()
    {
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', [ 'thread_id' => $threadWithTwoReplies->id ], 2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', [ 'thread_id' => $threadWithThreeReplies->id ], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([ 3, 2, 0 ], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    public function a_user_can_filter_threads_by_those_that_are_unanswered ()
    {
        $threadWithReplies = create('App\Thread');
        create('App\Reply', [ 'thread_id' => $threadWithReplies->id ], 2);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread ()
    {
        $thread = create('App\Thread');
        create('App\Reply', [ 'thread_id' => $thread->id ], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
