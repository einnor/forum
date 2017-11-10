<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);
        $search = 'foobar';
        create(Thread::class, [], 2);
        create(Thread::class, ['body' => "A thread with the {$search} term."], 2);

        $results = $this->getJson("/threads/search?q={$search}")->json();

        $this->assertCount(2, $results['data']);
    }
}
