<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{

    use DatabaseMigrations;

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
    public function a_user_can_read_replies_that_are_associated_with_a_thread ()
    {
        $reply = create('App\Reply', [ 'thread_id' => $this->thread->id ]);

        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);
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
}
