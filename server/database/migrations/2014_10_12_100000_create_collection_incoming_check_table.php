<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCollectionIncomingCheckTable extends Migration
{
  public function up()
  {
    Schema::create('collection_incoming_check', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('incoming_check_id')->unsigned();
      $table->integer('collection_id')->unsigned();
      $table->string('check_search_field')->nullable(true);
      $table->decimal('amount')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('incoming_check_id')->references('id')->on('incoming_checks');
      $table->foreign('collection_id')->references('id')->on('collections');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('collection_incoming_check');
  }
}
