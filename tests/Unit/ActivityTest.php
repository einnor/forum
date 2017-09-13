<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test*/
    public function it_records_an_activity_when_thread_is_created()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->assertDatabaseHas('activities', [
            'type'          =>  'created_thread',
            'user_id'       =>  auth()->id(),
            'subject_id'    =>  $thread->id,
            'subject_type'  =>  'App\Thread',
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject_id, $thread->id);
    }

    /** @test*/
    public function it_records_an_activity_when_reply_is_created()
    {
        $this->signIn();

        create(Reply::class);

        $this->assertCount(2, Activity::all());
    }

    /** @test*/
    public function it_fetches_feed_for_any_user()
    {
        $this->signIn();

        create(Thread::class, ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(Carbon::now()->format('Y-m-d')));

        $this->assertTrue($feed->keys()->contains(Carbon::now()->subWeek()->format('Y-m-d')));
    }
}
