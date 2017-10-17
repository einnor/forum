<?php

namespace App\Http\Controllers;

use App\Reply;

class BestReplyController extends Controller
{

    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * @param Reply $reply
     */
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);
        $reply->thread->markBestReply($reply);
    }
}
