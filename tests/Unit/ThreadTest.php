<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }
    
    /** @test */
    public function a_thread_has_a_path()
    {
        $thread = create(Thread::class);

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_have_a_reply()
    {
        $this->thread->addReply([
            'body'      =>  'Foobar',
            'user_id'   =>  1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body'      =>  'Foobar',
                'user_id'   =>  999
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create(Thread::class);

        tap(auth()->user(), function ($user) use ($thread) {

            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));

        });
        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_records_each_visit()
    {
        $thread = create(Thread::class, ['id' => 10]);

        $thread->visits()->reset();

        $this->assertEquals(0, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());
    }
}
