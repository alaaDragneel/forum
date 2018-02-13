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
                @foreach($activities as $date => $activityItems)
                    <h3 class="page-header">
                        {{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}
                    </h3>
                    @foreach($activityItems as $activity)
                        @include("profiles.activities.{$activity->type}")
                    @endforeach
                @endforeach
                {{--{{ $activities->links() }}--}}

            </div>
        </div>
    </div>
@endsection