<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadController extends Controller
{
    public function store(Thread $thread)
    {
        if(request()->has('locked')) {
            if(! auth()->user()->isAdmin()) {
                return response('You do not have permission to lock this thread', 403);
            }

            $thread->lock();
        }
    }
}
