<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{

    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * @param $channel
     * @param Request $request
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channel, Request $request, Thread $thread, Spam $spam)
    {
        $this->validate($request, [
            'body'      =>  'required'
        ]);

        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body'      =>  $request->get('body'),
            'user_id'   =>  auth()->id()
        ]);

        if(request()->expectsJson()) return $reply->load('owner');

        return back()
            ->with('flash', 'Your reply has been recorded');
    }

    /**
     * @param Request $request
     * @param Reply $reply
     */
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
