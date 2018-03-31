{{-- Editing Question. --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <wysiwyg v-model="form.body" :value="form.body"></wysiwyg>
        </div>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs btn-info level-item" @click="editing = true" v-show="! editing">Edit</button>
            <button class="btn btn-xs btn-primary level-item" @click="update" v-show="editing">Update</button>
            <button class="btn btn-xs btn-warning level-item" @click="resetForm">Cancel</button>
            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-sm">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{-- Viewing The Question. --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                <img src="{{ $thread->owner->avatar_path }}" class="mr-1" alt="{{ $thread->owner->name }}" title="{{ $thread->owner->name }}" width="25" height="25">
                <a href="{{ route('profiles.show', ['profileUser' => $thread->owner]) }}">
                    {{ $thread->owner->name }}
                </a>
                Posted:
                <span v-text="title"></span>

            </span>
        </div>
    </div>
    <div class="panel-body" v-html="body"></div>
    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs btn-info" @click="editing = true">Edit</button>
    </div>
</div>

