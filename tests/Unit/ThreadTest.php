<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{

    use RefreshDatabase;

    protected $thread;

    public function setUp ()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_thread_has_a_path ()
    {
        $thread = create('App\Thread');
        $path = url("/threads/{$thread->channel->slug}/{$thread->slug}");
        $this->assertEquals($path, $thread->path());
    }

    /** @test */
    public function a_thread_has_creator ()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    public function a_thread_has_replies ()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply ()
    {
        $this->thread->addReply([
            'body'    => 'FooBar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added ()
    {
        // Fake Notifications
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body'    => 'FooBar',
                'user_id' => 999, // to make user receive the notification
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

    }

    /** @test */
    public function a_thread_belongs_to_a_channel ()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to ()
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
    public function a_thread_can_be_unsubscribed_from ()
    {
        // Given We Have A Thread
        $thread = create('App\Thread');

        //  User Who subscribe To The Thread
        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);


        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_authenticated_user_is_subscribed_to_it ()
    {
        // Given We Have A Thread
        $thread = create('App\Thread');

        //  User Who subscribe To The Thread
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies ()
    {
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function ($user) use ($thread)
        {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    public function a_thread_records_each_visit ()
    {
        $thread = make('App\Thread', [ 'id' => 1 ]);

        $thread->visits()->reset();
        $this->assertSame(0, $thread->visits()->count());

        $thread->visits()->record();
        $this->assertEquals(1, $thread->visits()->count());

        $thread->visits()->record();
        $this->assertEquals(2, $thread->visits()->count());
    }

    /** @test */
    public function a_threads_body_is_sanitized_automatically ()
    {
        $thread = make('App\Thread', [ 'body' => '<script>alert("bad")</script><h1>This Is Ok</h1>' ]);

        $this->assertEquals('<h1>This Is Ok</h1>', $thread->body);
    }
}

