@extends('layouts.app')

@section('content')
   <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {{-- Main Thread Section::start--}}
                    @component('profiles.activities.activity')
                        @slot('heading')
                            <a href="{{ route('profiles.show', ['profileUser' => $thread->owner]) }}">
                                {{ $thread->owner->name }}
                            </a>
                            Posted:
                            {{ $thread->title }}
                        @endslot
                        @slot('option')
                            @can('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-link btn-sm">Delete Thread</button>
                                </form>
                            @endcan
                        @endslot
                        @slot('body')
                            {{ $thread->body }}
                        @endslot
                    @endcomponent
                    {{-- Main Thread Section::end--}}

                    <replies :data="{{ $thread->replies }}" @add-reply="repliesCount++" @remove-reply="repliesCount--"></replies>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }}
                                by
                                <a href="{{ route('profiles.show', ['profileUser' => $thread->owner]) }}">{{ $thread->owner->name }}</a>,
                                and currently has 
                                <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </thread-view>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // highlight the selected favorite
            if (window.location.hash != '') {
                var hash = window.location.hash.replace(/#/g, '');
                $('#' + hash).toggleClass('panel-success');
            }
        });
    </script>
@endsection