<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{

    /**
     * ThreadsController constructor.
     */
    public function __construct ()
    {
        $this->middleware('auth')->except([ 'index', 'show' ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index (Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if ( request()->wantsJson() ) { // for the test don't remove it
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads (Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ( $channel->exists ) $threads->where('channel_id', $channel->id);

        return $threads->paginate(25);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create ()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request)
    {
        $this->validate($request, [
            'title'      => 'required|spamfree',
            'body'       => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
        ]);


        $thread = Thread::create([
            'title'      => request('title'),
            'body'       => request('body'),
            'channel_id' => request('channel_id'),
            'user_id'    => auth()->id(),
        ]);

        return redirect($thread->path())->with('flash', 'Your Thread Has Been Published');
    }

    /**
     * Display the specified resource.
     *
     * @param $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show ($channel, Thread $thread)
    {
        if ( auth()->check() ) {
            auth()->user()->read($thread);
        }

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit (Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update (Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy ($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if ( request()->wantsJson() ) { // for test don't remove
            return response([], 204);
        }

        return redirect('/threads')->with('flash', 'Your Thread Deleted Was Successfully');
    }
}
