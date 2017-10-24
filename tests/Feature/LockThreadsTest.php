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
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn()->withExceptionHandling();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread), [
            'locked'    =>  true,
        ])->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        // Logic to create an admin
        $this->signIn(factory(User::class)->states('admin')->create());

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread), [
            'locked'    =>  true,
        ]);

        $this->assertTrue(!! $thread->fresh()->locked, 'Failed asserting that the thread is locked');
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body'      =>  'Foobar',
            'user_id'   =>  auth()->id()
        ])->assertStatus(422);
    }
}
