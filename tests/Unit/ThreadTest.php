<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_thread_can_make_a_path()
    {
        $thread = create('App\Thread');
        $path = url("/threads/{$thread->channel->slug}/{$thread->id}");
        $this->assertEquals($path, $thread->path());
    }

    /** @test */
    public function a_thread_has_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'FooBar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        // Given We Have A Thread
        $thread = create('App\Thread');

        // When The User subscribe To The Thread
        $thread->subscribe($userId = 1);

        // Then We Should Be Able To Fetch All Threads That The User Has Subscribed To.
        $subscriptions = $thread->subscriptions()->where('user_id', $userId)->count();
        $this->assertEquals(1, $subscriptions);
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        // Given We Have A Thread
        $thread = create('App\Thread');

        //  User Who subscribe To The Thread
        $thread->subscribe($userId = 1);
        
        $thread->unsubscribe($userId);

        
        $this->assertCount(0, $thread->subscriptions);
    }
    /** @test */
    public function it_knows_if_authenticated_user_is_subscribed_to_it()
    {
        // Given We Have A Thread
        $thread = create('App\Thread');

        //  User Who subscribe To The Thread
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

}
