<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableShopee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('shop_id');
            $table->string('item_id')->unique();
            $table->string('keyword');
            $table->string('url');
            $table->string('name')->nullable();
            $table->string('image_cover');
            $table->string('images')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('brand')->nullable();
            $table->integer('price')->nullable();
            $table->integer('price_before_discount')->nullable();
            $table->string('nature')->nullable();
            $table->string('item_rating')->nullable();
            $table->string('shop_location')->nullable();
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
        Schema::dropIfExists('table_shopee');
    }
}
