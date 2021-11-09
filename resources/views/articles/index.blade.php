@extends('layouts.master')

@section('content')
    <h2 class="text-center">Danh sách Articles</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tttle</th>
                                <th>Body</th>
                                <th>Tags#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->body }}</td>
                                    <td>{{ $item->tags }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection
