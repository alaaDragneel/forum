<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
{

    public function show (User $profileUser)
    {
        return view('profiles.show', [
            'profileUser' => $profileUser,
            'activities'  => Activity::feed($profileUser),
        ]);
    }

}
