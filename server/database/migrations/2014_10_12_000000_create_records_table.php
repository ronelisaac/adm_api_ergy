<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            
            
            $table->string('record_id');
            $table->string('form_code');
            $table->string('form_title');
            $table->string('name');
            $table->string('period')->nullable(true);
            $table->string('due_date')->nullable(true);
            $table->longText('t1')->nullable(true);
            $table->longText('t2')->nullable(true);
            $table->longText('t3')->nullable(true);
            $table->longText('search_field')->default('');
            $table->boolean('done')->nullable(true);
            $table->datetime('done_at')->nullable(true);
            $table->integer('done_by')->nullable(true);
            $table->boolean('cancelled')->nullable(true)->default(false);
            $table->datetime('cancelled_at')->nullable(true);
            $table->integer('cancelled_by')->nullable(true);
            $table->boolean('blocked')->nullable(true)->default(false);
            $table->datetime('blocked_at')->nullable(true);
            $table->integer('blocked_by')->nullable(true);
            $table->boolean('observed')->nullable(true)->default(false);
            $table->datetime('observed_at')->nullable(true);
            $table->integer('observed_by')->nullable(true);
            $table->longText('observation')->nullable(true);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->softDeletes();
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
        Schema::dropIfExists('records');
    }
}