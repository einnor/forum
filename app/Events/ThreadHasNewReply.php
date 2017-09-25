<?php

namespace App\Events;

use App\Reply;
use App\Thread;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadHasNewReply
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Reply
     */
    public $reply;
    /**
     * @var Thread
     */
    public $thread;

    /**
     * Create a new event instance.
     *
     * @param Thread $thread
     * @param Reply $reply
     */
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->reply = $reply;

        $this->thread = $thread;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
