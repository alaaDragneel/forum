@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profiles.show', ['profileUser' => $activity->subject->owner]) }}"> {{ $activity->subject->owner->name }} </a> Reply To
        <a href="{{ $activity->subject->thread->path() }}">
            "{{ $activity->subject->thread->title }}"
        </a>
    @endslot
    @slot('option')
        {{ $activity->subject->created_at->diffForHumans() }}
    @endslot
    @slot('body')
        {!!  $activity->subject->body !!}
    @endslot
@endcomponent