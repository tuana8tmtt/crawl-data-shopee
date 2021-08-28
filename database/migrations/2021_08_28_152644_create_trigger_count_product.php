<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerCountProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER count_product AFTER INSERT ON product
        FOR EACH ROW
        BEGIN
          UPDATE keywords SET quantity = (select count(*) from product where product.keyword = keywords.Keyword) WHERE Keyword = NEW.keyword;
        END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `count_product`');
    }
}
