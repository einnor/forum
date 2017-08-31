<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    /** @test */
    public function unauthenticated_user_may_not_add_reply()
    {
        $this->withExceptionHandling('Illuminate\Auth\AuthenticationException')
        ->post('/threads/some-channel/1/replies', [])
        ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class);

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_replay_requires_a_body()
    {
        $this->withExceptionHandling()
            ->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
