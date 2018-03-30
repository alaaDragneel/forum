<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp ()
    {
        parent::setUp();
        $this->withExceptionHandling();

        $this->signIn();
    }


    /** @test */
    public function un_authorized_users_may_not_update_threads ()
    {
        $thread = create('App\Thread', [ 'user_id' => create('App\User')->id ]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }
    
    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated ()
    {

        $thread = create('App\Thread', [ 'user_id' => auth()->id() ]);

        $this->patch($thread->path(), [
            'title' => 'Updated Title',
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Updated Body',
        ])->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_thread_can_be_updated_by_its_creator ()
    {
        $thread = create('App\Thread', [ 'user_id' => auth()->id() ]);

        $this->patch($thread->path(), [
            'title' => 'Updated Title',
            'body'  => 'Updated Body',
        ]);

        tap($thread->fresh(), function ($thread)
        {
            $this->assertEquals('Updated Title', $thread->title);
            $this->assertEquals('Updated Body', $thread->body);
        });
    }


}
