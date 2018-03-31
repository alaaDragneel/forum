@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profiles.show', ['profileUser' => $activity->subject->owner]) }}"> {{ $activity->subject->owner->name }} </a> Published a Thread:
        <a href="{{ $activity->subject->path() }}">
            {{ $activity->subject->title }}
        </a>
    @endslot
    @slot('option')
        {{ $activity->subject->created_at->diffForHumans() }}
    @endslot
    @slot('body')
        {!!  $activity->subject->body !!}
    @endslot
@endcomponent


