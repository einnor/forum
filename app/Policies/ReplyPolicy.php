<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Reply $reply
     * @return bool
     */
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        $lastReply = $user->fresh()->lastReply;

        if(! $lastReply) return true;

        return ! $lastReply->wasJustPublished();
    }
}
