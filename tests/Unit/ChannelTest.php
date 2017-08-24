<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    protected $channel;

    public function setUp()
    {
        parent::setUp();

        $this->channel = create(Channel::class);
    }

    /** @test*/
    public function a_channel_consists_of_threads()
    {
        $thread = create(Thread::class, ['channel_id' => $this->channel->id]);

        $this->assertTrue($this->channel->threads->contains($thread));
    }
}
