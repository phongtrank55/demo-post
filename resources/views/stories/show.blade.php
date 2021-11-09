@extends('layouts.master')

@section('content')
    <h1 class="text-center">{{ $story->name }}</h1>
    @foreach ($chapters as $chapter)
        <hr>
        @if(!empty($chapter->audio))
            <audio src="{{ $chapter->audio }}" controls></audio>
        @endif
        <h3>{{ $chapter->name }}</h3>
        <div>
            {!! $chapter->content !!}
        </div>
    @endforeach
@endsection
