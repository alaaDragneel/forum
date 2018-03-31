@forelse($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5> Posted By:
                        <a href="{{ route('profiles.show', $thread->owner) }}"> {{ $thread->owner->name }} </a>
                    </h5>
                </div>
                <span>
                     <a href="{{ $thread->path() }}">
                        {{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count) }}
                     </a>
                </span>
            </div>
        </div>
        <div class="panel-body">
             {!! $thread->body !!}
        </div>

        <div class="panel-footer">
            {{ $thread->visits()->count() }}
            <span>Visits</span>
        </div>
    </div>
@empty
    <div class="alert alert-info text-center">No Threads Founds.</div>
@endforelse