<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('phones')->truncate();
        DB::table('fits')->truncate();
        DB::table('accessories')->truncate();
        DB::table('invoice_details')->truncate();

        DB::table('phones')->insert([
            ['id' => 1, 'name' => 'iPhone 11'],
            ['id' => 2, 'name' => 'Samsung Galaxy 8'],
            ['id' => 3, 'name' => 'Xiaomi MI 10'],
        ]);

        DB::table('fits')->insert([
            ['id' => 1, 'name' => 'Tai nghe Airport 2'],
            ['id' => 2, 'name' => 'Bao da iPhone 11 KST'],
        ]);

        DB::table('accessories')->insert([
            ['id' => 1, 'name' => 'Anten wifi iPad 2'],
            ['id' => 2, 'name' => 'Camera sau iPhone 11'],
            ['id' => 3, 'name' => '	Xương Samsung Galaxy A7 2018 Xanh'],
        ]);

        DB::table('invoice_details')->insert([
            ['invoice_id' => 2, 'product_type' => 'phone', 'product_id' => 1, 'quantity' => 1, 'price' => 10000000],
            ['invoice_id' => 4, 'product_type' => 'phone', 'product_id' => 2, 'quantity' => 1, 'price' => 12000000],
            ['invoice_id' => 4, 'product_type' => 'fit', 'product_id' => 1, 'quantity' => 2, 'price' => 200000],
            ['invoice_id' => 5, 'product_type' => 'accessory', 'product_id' => 1, 'quantity' => 1, 'price' => 30000],
            ['invoice_id' => 5, 'product_type' => 'accessory', 'product_id' => 2, 'quantity' => 2, 'price' => 50000],
            ['invoice_id' => 6, 'product_type' => 'fit', 'product_id' => 2, 'quantity' => 1, 'price' => 50000],
        ]);
    }
}
