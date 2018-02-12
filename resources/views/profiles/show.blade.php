@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                        <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                    </h1>
                </div>
                @foreach($threads as $thread)
                    {{-- Main Thread Section::start--}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                    <span class="flex">
                                        <a href="{{ $thread->path() }}">
                                            {{ $thread->title }}
                                        </a>
                                    </span>
                                    <span>
                                        {{ $thread->created_at->diffForHumans() }}
                                    </span>
                            </div>
                        </div>
                        <div class="panel-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                    {{-- Main Thread Section::end--}}
                @endforeach
                {{ $threads->links() }}

            </div>
        </div>
    </div>
@endsection