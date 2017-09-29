<?php

namespace Tests\Unit;

use App\Reply;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    public function it_knows_it_it_was_just_published()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new Reply([
            'body' => 'Hello @JaneDoe.'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_metioned_usernames_within_the_body_in_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hello @JaneDoe.'
        ]);

        $this->assertEquals('Hello <a href="/profiles/JaneDoe">@JaneDoe</a>.', $reply->mentionedUsers());
    }
}
