<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration ()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'alaa',
            'email'                 => 'alaa@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses ()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'alaa',
            'email'                 => 'alaa@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        $user = User::where('name', 'alaa')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        // let the user confirm their account

        $this->get(route('register.confirm', [ 'token' => $user->confirmation_token ]))
            ->assertRedirect(route('threads.index'));

        tap($user->fresh(), function ($user)
        {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });

    }

    /** @test */
    public function confirming_an_invalid_token ()
    {
        $this->get(route('register.confirm', [ 'token' => 'invalid' ]))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'Unknown Token.');

    }
}
