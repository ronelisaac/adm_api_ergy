<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameNetAmountInPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('purchases', function (Blueprint $table) {
        //     //
        //     //$table->renameColumn('net_amount', 'net_amount_1');
        // });
        DB::statement("ALTER TABLE `purchases` CHANGE `net_amount` `net_amount_1` decimal(8,2)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
            //$table->renameColumn('net_amount_1', 'net_amount');
        });
    }
}
