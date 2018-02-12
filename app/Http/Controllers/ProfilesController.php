<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
{

    public function show (User $profileUser)
    {
        return view('profiles.show', [
            'profileUser' => $profileUser,
            'threads'       => $profileUser->threads()->paginate(30)
        ]);
    }

}
