<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp ()
    {
        parent::setUp();
        $this->signIn();

    }


    /** @test */
    public function a_notifications_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user ()
    {

        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // Then, Each Time A New Reply Is Left.
        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some Reply Here',
        ]);

        // A Notification Should Be Prepared For The User.
        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body'    => 'Some Reply Here',
        ]);

        // A Notification Should Be Prepared For The User.
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }


    /** @test */
    public function a_user_can_fetch_their_unread_notification ()
    {
        create(DatabaseNotification::class);

        $user = auth()->user();

        $response = $this->getJson("/profiles/{$user->name}/notifications")->json();

        $this->assertCount(1, $response);

    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read ()
    {
        create(DatabaseNotification::class);

        tap(auth()->user(), function ($user)
        {
            // A Notification Should Be Prepared For The User.
            $this->assertCount(1, $user->fresh()->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;

            $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

            $this->assertCount(0, $user->fresh()->unreadNotifications);

        });


    }

}
