<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Thread;

class ReplyController extends Controller
{

    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * @param $channel
     * @param Thread $thread
     * @param CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($channel, Thread $thread, CreatePostRequest $form)
    {
        if($thread->locked) return response('Thread is locked', 422);

        return $form->persist($thread);
    }

    /**
     * @param Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required | spamfree']);

        $reply->update(['body' => request('body')]);
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
