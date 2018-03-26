<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;

class UsersAvatarController extends Controller
{

    public function store ($user)
    {
        $user = User::findOrFail($user);

        request()->validate([ 'avatar' => 'required|image' ]); // laravel 5.5 new validate way

        $user->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public'),
        ]);


        return response([], 204);
    }

}
