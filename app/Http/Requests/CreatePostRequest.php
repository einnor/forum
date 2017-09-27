<?php

namespace App\Http\Requests;

use app\Exceptions\ThrottleException;
use App\Reply;
use App\Thread;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Gate::allow('create', new Reply);
    }

    public function failedAuthorization()
    {
        throw new ThrottleException('You are replying too frequently. Please take a break :)');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required | spamfree'
        ];
    }

    /**
     * @param Thread $thread
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function persist(Thread $thread)
    {
        return $thread->addReply([
            'body'      =>  request('body'),
            'user_id'   =>  auth()->id()
        ])->load('owner');
    }
}
