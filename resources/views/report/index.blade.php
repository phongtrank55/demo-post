@extends('layouts.master')

@section('title', 'Báo cáo bán hàng')

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Hoá đơn</th>
                    <th class="text-center">Sản phẩm</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center">Số lượng </th>
                    <th class="text-center">Thành tiền</th>
                </tr>
                <tr>
                    <td>{{format_price(50000)}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

    </div>

@endsection