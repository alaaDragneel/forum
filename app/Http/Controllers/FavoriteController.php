<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{

    public function __construct ()
    {
        $this->middleware('auth');
    }

    public function store (Reply $reply)
    {
        $reply->favorite();
        // return back()->with('flash', 'You Favorite Reply Successfully');
    }

    public function destroy(Reply $reply)
    {
        $reply->unFavorite();
    }
}
