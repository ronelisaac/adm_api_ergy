<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('credits', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('record_id')->unsigned();
      $table->integer('user_id')->unsigned();
      $table->string('detail');
      $table->enum('currency', ["Peso","Dolar"])->nullable(true);
      $table->decimal('amount');
      $table->decimal('collected_amount')->nullable(true);
      $table->decimal('balance')->nullable(true);
      $table->date('due_date')->nullable(true);
      $table->boolean('collected')->nullable(true)->default(false);
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
    Schema::dropIfExists('credits');
  }
}