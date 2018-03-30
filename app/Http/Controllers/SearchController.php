<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;

class SearchController extends Controller
{

    public function show (Trending $trending)
    {
        $search = request('q');

        $threads = Thread::search($search)->paginate(25);
        
        if ( request()->wantsJson() ) {
            return $threads;
        }


        return view('threads.index', [
            'threads'  => $threads,
            'trending' => $trending->get(),
        ]);
    }

}
