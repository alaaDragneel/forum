<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserNotificationsController extends Controller
{

    public function __construct ()
    {
        return $this->middleware('auth');
    }

    public function index ()
    {
        return auth()->user()->unreadNotifications;
    }


    public function destroy (User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }

}
