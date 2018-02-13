@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @forelse($threads as $thread)
                    @component('profiles.activities.activity')
                        @slot('heading')
                            <a href="{{ $thread->path() }}">
                                {{ $thread->title }}
                            </a>
                        @endslot
                        @slot('option')
                                <a href="{{ $thread->path() }}" class="badge">{{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count) }} </a>
                        @endslot
                        @slot('body')
                                {{ $thread->body }}
                        @endslot
                    @endcomponent
                @empty
                    <div class="alert alert-info text-center">No Threads Founds.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
