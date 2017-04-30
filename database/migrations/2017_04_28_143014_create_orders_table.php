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
            $table->decimal('buy_amount', 10, 4)->default(0);
            $table->decimal('buy_price', 10, 4)->default(0);
            $table->decimal('buy_money', 10, 4)->default(0);
            $table->string('buy_id')->default('0');
            $table->decimal('sell_amount', 10, 4)->default(0);
            $table->decimal('sell_price', 10, 4)->default(0);
            $table->decimal('sell_money', 10, 4)->default(0);
            $table->string('sell_id')->default('0');

            $table->dateTime('last_buy_query')->nullable()->default(null);
            $table->dateTime('last_sell_query')->nullable()->default(null);

            $table->smallInteger('buy_status')->nullable()->default(0);
            $table->smallInteger('sell_status')->nullable()->default(0);


            $table->decimal('notice_amount', 10, 4)->default(0);


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
