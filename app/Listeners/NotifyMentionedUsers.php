<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     * NotifyMentionedUsers constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        // Inspect the body of the reply for username mentions
        collect($event->reply->mentionedUsers())
            ->map(function ($name) {
                return User::where('name', $name)->first();
            })
            ->filter()
            ->each
            ->notify(new YouWereMentioned($event->reply));
    }
}
