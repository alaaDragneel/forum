@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.min.css') }}">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template v-cloak>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @include('threads._questions')
                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>
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
                            <p>
                                <subscribe-button :active="'{{ $thread->isSubscribedTo }}'" v-if="signedIn"></subscribe-button>
                                <button
                                        class="btn btn-primary"
                                        v-if="authorize('isAdmin')"
                                        @click="toggleLock"
                                        v-text="locked ? 'Unlock' : 'Lock'">
                                    Lock
                                </button>
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