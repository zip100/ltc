<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->decimal('buy_price', 10, 2)->default(0)->change();
            $table->decimal('buy_money', 10, 2)->default(0)->change();
            $table->decimal('sell_price', 10, 2)->default(0)->change();
            $table->decimal('sell_money', 10, 2)->default(0)->change();

            $table->decimal('notice_amount', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
