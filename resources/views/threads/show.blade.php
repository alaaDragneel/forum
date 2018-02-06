@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#"> {{ $thread->owner->name }} </a> Posted: 
                    {{ $thread->title }}
                </div>
                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @foreach ($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>
    </div>

  <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
        </div>
    </div>
</div>
@endsection
