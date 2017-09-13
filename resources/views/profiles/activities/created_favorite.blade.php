@component('profiles.activities.activity')
    @slot('heading')
        <i class="glyphicon glyphicon-heart"></i> {{ $profileUser->name }} favorited a <a href="{{ $activity->subject->favorited->path() }}">reply</a>
{{--        <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>--}}
    @endslot
    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent