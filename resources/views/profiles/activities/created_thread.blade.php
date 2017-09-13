@component('profiles.activities.activity')
    @slot('heading')
        <i class="glyphicon glyphicon-pencil"></i> {{ $profileUser->name }} published a
        <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent