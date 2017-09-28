<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;

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
//        return $form->persist($thread);

        $reply =  $thread->addReply([
            'body'      =>  request('body'),
            'user_id'   =>  auth()->id()
        ]);

        // Inspect the body of the reply for username mentions
        preg_match_all('/\@([^\s\.]+)/', request('body'), $matches);

        $names = $matches[0];

        foreach ($names as $name) {
            $user = User::whereIn('name', $name)->first();

            $user->notify(new YouWereMentioned($reply));
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
