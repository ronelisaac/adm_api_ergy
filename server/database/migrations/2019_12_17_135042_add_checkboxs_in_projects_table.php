<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckboxsInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->boolean('bank_transfer')->nullable(true);
            $table->boolean('cash')->nullable(true);
            $table->boolean('bank_deposit')->nullable(true);
            $table->boolean('check')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->dropColumn('bank_transfer');
            $table->dropColumn('cash');
            $table->dropColumn('bank_deposit');
            $table->dropColumn('check');
        });
    }
}
