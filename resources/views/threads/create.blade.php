@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create A New Thread</div>
                    <div class="panel-body">
                        <form method="post" action="{{ route('threads.store') }}">
                            {{ csrf_field() }}
                            {{-- Title Section::Start --}}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" id="title" name="title" class="form-control title" placeholder="Title Here">
                            </div>
                            {{-- Title Section::End--}}
                            {{-- Body Section::Start --}}
                            <div class="form-group">
                                <label for="body">Body</label>
                                <textarea id="body" name="body" class="form-control body" rows="8" placeholder="Body Here"></textarea>
                            </div>
                            {{-- Body Section::End--}}
                            <button class="btn btn-success">Publish</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
