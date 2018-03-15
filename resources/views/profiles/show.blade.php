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
                @forelse($activities as $date => $activityItems)
                    <h3 class="page-header">
                        {{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}
                    </h3>
                    @foreach($activityItems as $activity)
                        @if (view()->exists("profiles.activities.{$activity->type}"))
                            @include("profiles.activities.{$activity->type}")
                        @endif
                    @endforeach
                @empty
                    <p>There Is Now Activity For This User Yet.</p>
                @endforelse
                {{--{{ $activities->links() }}--}}

            </div>
        </div>
    </div>
@endsection