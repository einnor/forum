<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * UserNotificationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return auth()->user()->unreadNotifications;
    }

    /**
     * @param User $user
     * @param $id
     */
    public function destroy(User $user, $id)
    {
        auth()->user()
            ->notifications()
            ->findOrFail($id)
            ->markAsRead();
    }
}
