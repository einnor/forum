@component('profiles.activities.activity')
    @slot('heading')
        <i class="glyphicon glyphicon-comment"></i> {{ $profileUser->name }} replied to
        <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
    @endslot
    @slot('body')
        {{ $activity->subject->thread->body }}
    @endslot
@endcomponent