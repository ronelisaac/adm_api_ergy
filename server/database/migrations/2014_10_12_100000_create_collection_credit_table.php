<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCollectionCreditTable extends Migration
{
  public function up()
  {
    Schema::create('collection_credit', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('credit_id')->unsigned();
      $table->integer('collection_id')->unsigned();
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('credit_id')->references('id')->on('credits');
      $table->foreign('collection_id')->references('id')->on('collections');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('collection_credit');
  }
}