<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionUsersTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create(User::class, ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create(User::class, ['name' => 'JaneDoe']);

        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'body' => '@JaneDoe look at this',
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function it_can_fetch_all_given_users_starting_with_the_given_characters()
    {
        create(User::class, ['name' => 'JohnDoe']);
        create(User::class, ['name' => 'JaneDoe']);
        create(User::class, ['name' => 'JohnDoe2']);

        $results = $this->json('GET', '/api/users', ['name' => 'John']);

        $this->assertCount(2, $results->json());
    }
}
