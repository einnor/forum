<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->withExceptionHandling()
            ->signIn();

    }

    /** @test */
    public function unauthorized_users_may_not_update_threads()
    {
        $thread = create(Thread::class, ['user_id' => create(User::class)->id]);

        $this->patchJson($thread->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_thread_requires_a_title_and_a_body_to_be_updated()
    {
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'body'  =>  'Changed body',
        ])->assertSessionHasErrors('title');

        $this->patch($thread->path(), [
            'title' =>  'Changed Title',
        ])->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patchJson($thread->path(), [
            'title' =>  'Changed Title',
            'body'  =>  'Changed body',
        ]);

        tap($thread->fresh(), function($thread) {
            $this->assertEquals('Changed Title', $thread->title);
            $this->assertEquals('Changed body', $thread->body);
        });
    }
}
