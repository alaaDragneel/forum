<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="#">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }} ...
            </h5>
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
</div>
