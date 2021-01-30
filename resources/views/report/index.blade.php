@extends('layouts.master')

@section('title', 'Báo cáo bán hàng')

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Hoá đơn</th>
                    <th class="text-center">ID sản phẩm</th>
                    <th class="text-center">Sản phẩm</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center">Số lượng </th>
                    <th class="text-center">Thành tiền</th>
                </tr>
                @php $index = 1; @endphp
                @foreach ($report as $item)
                    <tr>
                        <td class="text-center">{{ $index++ }}</td>
                        <td class="text-center"><a href="#">#HĐ{{ $item->invoice_id }}</a></td>
                        <td class="text-center"><strong>{{$item->product_id}}</strong></td>
                        <td>
                            @switch($item->product_type)
                                @case('phone')
                                    <span class="label label-info">Điện thoại</span>
                                    @break
                                @case('fit')
                                    <span class="label label-success">Phụ kiện</span>
                                    @break
                                @case('accessory')
                                    <span class="label label-warning">Linh kiện</span>
                                    @break
                                @default

                            @endswitch
                            {{ $item->product->name }}
                        </td>
                        <td class="text-right">{{ format_price($item->price) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ format_price($item->price *  $item->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection