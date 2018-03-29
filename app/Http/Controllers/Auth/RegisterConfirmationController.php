<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

class RegisterConfirmationController extends Controller
{

    public function index ()
    {

        $user = User::where('confirmation_token', request('token'))->first();

        if ( ! $user ) {
            return redirect(route('threads.index'))->with('flash', 'Unknown Token.');
        }

        $user->confirmed();

        return redirect(route('threads.index'))->with('flash', 'Your Account Is Now Confirmed! You May Post To The Forum.');

    }

}
