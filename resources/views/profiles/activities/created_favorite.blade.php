@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $profileUser->name }} Favorite a Reply.
        </a>

    @endslot
    @slot('option')
        {{ $activity->subject->favorited->created_at->diffForHumans() }}
    @endslot
    @slot('body')
        {!! $activity->subject->favorited->body !!}
    @endslot
@endcomponent