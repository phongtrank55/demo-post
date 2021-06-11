@extends('layouts.master')

@section('content')
    <h2>Danh sách truyện</h2>
    <ul>
        @foreach ($stories as $story)
            <li><a href="{{ route('stories.show', ['id' => $story->id]) }}">{{ $story->name ?? '' }}</a></li>
        @endforeach
    </ul>
@endsection