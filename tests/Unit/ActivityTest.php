<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
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
}
