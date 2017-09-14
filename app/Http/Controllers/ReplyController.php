<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{

    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param $channel
     * @param Request $request
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channel, Request $request, Thread $thread)
    {
        $this->validate($request, [
            'body'      =>  'required'
        ]);

        $thread->addReply([
            'body'      =>  $request->get('body'),
            'user_id'   =>  auth()->id()
        ]);

        return back()
            ->with('flash', 'Your reply has been recorded');
    }

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update([
            'body' => $request->body
        ]);
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->wantsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return redirect()->back()->with('flash', 'Reply has been removed');
    }
}
