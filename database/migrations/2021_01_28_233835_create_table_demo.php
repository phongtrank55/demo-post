<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDemo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('note', 500)->nullable();
            $table->timestamps();
        });
        Schema::create('fits', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('note', 500)->nullable();
            $table->timestamps();
        });
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('note', 500)->nullable();
            $table->timestamps();
        });
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->string('product_type', 50)->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('price')->default(0);
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phones');
        Schema::dropIfExists('fits');
        Schema::dropIfExists('accessories');
        Schema::dropIfExists('invoice_details');
    }
}
