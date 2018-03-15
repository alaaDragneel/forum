{{--  v-cloak will be removed by vue when the page and the vue loaded See app.blade.php and you will see the styling   --}}
<reply :data="{{ $reply }}" inline-template v-cloak>
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
                    @if (auth()->check())
                        <favorite :reply="{{ $reply }}" ></favorite>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing" >
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary btn-xs" @click="update">Update</button>
                <button class="btn btn-link btn-xs" @click="editing = false" >Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-info btn-xs mr-1" @click="editing = true" >Edit</button>
                <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
    
</reply>