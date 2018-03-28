<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration ()
    {
        // More Code Can Found In EventServiceProvider
        Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses ()
    {
        $this->post('/register', [
            'name'                  => 'alaa',
            'email'                 => 'alaa@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        $user = User::where('name', 'alaa')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        // let the user confirm their account

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }

}
