<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * @return string
     */
    public function cacheKey()
    {
        return app()->environment() == 'testing' ? 'trending_threads_test' : 'trending_threads';
    }

    /**
     * @return array
     */
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * @param Thread $thread
     */
    public function push(Thread $thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'url' => $thread->path()
        ]));
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}