@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                {{-- Main Thread Section::start--}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#"> {{ $thread->owner->name }} </a> Posted:
                        {{ $thread->title }}
                    </div>
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>
                {{-- Main Thread Section::end--}}

                {{-- Reply Section::start --}}
                @foreach ($replies as $reply)
                    @include('threads.reply')
                @endforeach
                {{ $replies->links() }}
                {{-- Reply Section::end --}}
                {{-- Reply Form Section::start --}}
                @if (auth()->check())
                    <form method="POST" action="{{ $thread->path() . '/replies' }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have Something To Say ?" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Post</button>
                    </form>
                @else
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Want Join The Discussion</h4>
                        <p>Please <a href=" {{ route('login') }} ">Login</a> To Join This Discussion</p>
                    </div>
                @endif
                {{-- Reply Form Section::end --}}
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }}
                            by <a href="#">{{ $thread->owner->name }}</a>,
                            and currently has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
@endsection
