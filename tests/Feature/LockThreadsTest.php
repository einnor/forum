<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_administrator_can_lock_any_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->lock();

        $this->post($thread->path() . '/replies', [
            'body'      =>  'Foobar',
            'user_id'   =>  auth()->id()
        ])->assertStatus(422);
    }
}
