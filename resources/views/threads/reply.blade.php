<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <a href="{{ route('profiles.show', ['profileUser' => $thread->owner]) }}">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }} ...
            </span>
            <div>
                <form action="{{ route('favorites.replies.store', ['reply' => $reply->id]) }}" method="post">
                    {{ csrf_field() }}
                    <button class="btn btn-danger btn-sm" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        {{ $reply->body }}
    </div>
    @can('update', $reply)
        <div class="panel-footer">
            <form action="{{ route('replies.destroy', $reply) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
            </form>
        </div>
    @endcan
</div>
