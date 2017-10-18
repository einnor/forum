<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-reply.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->signIn(create(User::class));

        $this->postJson(route('best-reply.store', [$reply->id]))->assertStatus(403);

        $this->assertFalse($reply->fresh()->isBest());
    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_is_updated_to_reflect_that()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->postJson(route('replies.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
