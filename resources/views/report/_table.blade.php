@php
    $details = [
        ['product_id' => 322, 'product_name' => 'Điện thoại xiaomi MI 5X Xanh 6-64GB Hàn Quốc', 'price' => 4600000 , 'quantity' => 'ADXFD23343', 'real_price' => 46000000],
        ['product_id' => 322, 'product_name' => 'Điện thoại xiaomi MI 5X Xanh 6-64GB Hàn Quốc', 'price' => 4600000 , 'quantity' => 'ADXFD23343', 'real_price' => 46000000],
        ['product_id' => 344, 'product_name' => 'Tai nghe Airport 2:1', 'price' => 50000, 'quantity' => 2, 'real_price' => 100000],
        ['product_id' => 344, 'product_name' => 'Tai nghe Airport 2:1', 'price' => 50000, 'quantity' => 2, 'real_price' => 100000],
        ['product_id' => 344, 'product_name' => 'Tai nghe Airport 2:1', 'price' => 50000, 'quantity' => 2, 'real_price' => 100000],
    ];
@endphp

<h1>Hoá đơn</h1>
{{-- <table border="1" cellspacing="0" cellpadding="5"> --}}
<table align="center" style="border: 1px #000 solid;">
    <thead>
        <tr style="background-color: blue; text-align: center; color: #FFFFFF; font-weight: bold; ">
            <th style="width: 20pt; margin-top: 50pt">#</th>
            <th style="padding: 15px">&nbsp;&nbsp;Sản phẩm</th>
            <th style="width: 50pt">Đơn giá</th>
            <th style="width: 80pt">IMEI / Số lượng</th>
            <th style="width: 70pt">Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail['product_name'] ?? '' }}</td>
                <td>{{ $detail['price'] ?? '' }}</td>
                <td>{{ $detail['quantity'] ?? '' }}</td>
                <td>{{ $detail['real_price'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
