<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try{
            User::where('confirmation_token', request('token'))
                ->firstOrFail()
                ->confirm();
        }catch (\Exception $e){
            return redirect(route('threads'))
                ->with('flash', 'Invalid token');
        }

        return redirect('/threads')
            ->with('flash', 'You account is now confirmed. You may post to the forum.');
    }
}
