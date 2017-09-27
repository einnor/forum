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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($channel, Thread $thread, Spam $spam)
    {
        try{
            $this->validateReply();

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
     * @param Spam $spam
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

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

    private function validateReply()
    {
        $this->validate(request(), [
            'body'      =>  'required'
        ]);

        resolve(Spam::class)->detect(request('body'));
    }
}
