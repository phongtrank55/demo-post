@extends('layouts.master')

@section('content')
    <audio controls autoplay>
        <source src="{{ $data }}" type="audio/mpeg">
        "Trình duyệt không hỗ trợ"
    </audio>
@endsection
