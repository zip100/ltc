<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->smallInteger('type')->default(0);
            $table->decimal('buy_amount')->default(0);
            $table->decimal('buy_price')->default(0);
            $table->decimal('buy_money')->default(0);
            $table->integer('buy_id')->default(0);
            $table->decimal('sell_amount')->default(0);
            $table->decimal('sell_price')->default(0);
            $table->decimal('sell_money')->default(0);
            $table->integer('sell_id')->default(0);

            $table->dateTime('last_buy_query')->default(null);
            $table->dateTime('last_sell_query')->default(null);

            $table->smallInteger('buy_status')->default(0);
            $table->smallInteger('sell_status')->default(0);

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
        Schema::dropIfExists('orders');
    }
}
