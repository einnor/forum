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
     * @param Spam $spam
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Database\Eloquent\Model
     */
    public function store($channel, Thread $thread, Spam $spam)
    {
        $this->validateReply();

        $reply = $thread->addReply([
            'body'      =>  request('body'),
            'user_id'   =>  auth()->id()
        ]);

        if(request()->expectsJson()) return $reply->load('owner');

        return back()
            ->with('flash', 'Your reply has been recorded');
    }

    /**
     * @param Reply $reply
     * @param Spam $spam
     */
    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        $this->validateReply();

        $reply->update([
            'body' => request('body')
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

    private function validateReply()
    {
        $this->validate(request(), [
            'body'      =>  'required'
        ]);

        resolve(Spam::class)->detect(request('body'));
    }
}
