<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WhenThreadHasNewReply
{
    /**
     * Create the event listener.
     *
     * WhenThreadHasNewReply constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $thread = $event->thread;

        $reply = $event->reply;

        // Prepare notifications for all subscribers
        $thread->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }
}
