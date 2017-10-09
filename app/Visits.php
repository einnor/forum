<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    /**
     * @var Thread
     */
    private $thread;

    /**
     * Visits constructor.
     * @param Thread $thread
     */
    public function __construct(Thread $thread)
    {

        $this->thread = $thread;
    }

    /**
     * @return int
     */
    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    /**
     * @return string
     */
    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }

    public function record()
    {
        Redis::incr($this->cacheKey());
    }
}