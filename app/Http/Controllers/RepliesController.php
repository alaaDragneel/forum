<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{

    /**
     * RepliesController constructor.
     */
    public function __construct ()
    {
        $this->middleware('auth', [ 'except' => 'index' ]);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index ($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @param CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public function store ($channelId, Thread $thread, CreatePostRequest $form)
    {
        if ( $thread->locked ) {
            return response('Thread Is Locked', 422);
        }

        return $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner');
    }

    /**
     * @param Reply $reply
     */
    public function update (Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate([ 'body' => 'required|spamfree' ]);

        $reply->update(request([ 'body' ]));
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy (Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if ( request()->expectsJson() ) {
            return response([ 'status' => 'Reply Deleted Successfully' ]);
        }

        return back()->with('flash', 'Reply Deleted Successfully');
    }

}
