<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_user_has_a_profile ()
    {
        $profileUser = create('App\User');
        $this->get('/profiles/' . $profileUser->name)
            ->assertSee($profileUser->name);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_associated_user ()
    {
        $this->signIn();

        $thread = create('App\Thread', [ 'user_id' => auth()->id() ]);
        $this->get('/profiles/' . auth()->user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

}
