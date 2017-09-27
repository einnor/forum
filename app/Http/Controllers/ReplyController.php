<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($channel, Thread $thread)
    {
        if(Gate::denies('create', new Reply)) {
            return response('You are posting too frequently, Please take a break :)', 422);
        }
        
        try{
            $this->validate(request(), ['body' => 'required | spamfree']);

            $reply = $thread->addReply([
                'body'      =>  request('body'),
                'user_id'   =>  auth()->id()
            ]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time', 422);
        }

        return $reply->load('owner');
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Reply $reply)
    {
        $this->validate(request(), ['body' => 'required | spamfree']);

        try{
            $this->validateReply();

            $reply->update([
                'body' => request('body')
            ]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be updated at this time', 422);
        }
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
