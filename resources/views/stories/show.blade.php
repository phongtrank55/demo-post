@extends('layouts.master')

@section('content')
    <h1 class="text-center">{{ $story->name }}</h1>
    @foreach ($chapters as $chapter)
        <hr>
        <h3>{{ $chapter->name }}</h3>
        <div>
            {!! $chapter->content !!}
        </div>
    @endforeach
@endsection