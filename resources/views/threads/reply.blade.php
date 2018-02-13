@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ route('profiles.show', ['profileUser' => $thread->owner]) }}">
            {{ $reply->owner->name }}
        </a>
        said {{ $reply->created_at->diffForHumans() }} ...
    @endslot
    @slot('option')
        <div>
            <form action="{{ route('favorites.replies.store', ['reply' => $reply->id]) }}" method="post">
                {{ csrf_field() }}
                <button class="btn btn-danger btn-sm" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                </button>
            </form>
        </div>
    @endslot
    @slot('body')
        {{ $reply->body }}
    @endslot
@endcomponent