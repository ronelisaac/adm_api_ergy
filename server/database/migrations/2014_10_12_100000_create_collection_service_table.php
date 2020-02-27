<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCollectionServiceTable extends Migration
{
  public function up()
  {
    Schema::create('collection_service', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('service_id')->unsigned();
      $table->integer('collection_id')->unsigned();
      $table->string('name')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('service_id')->references('id')->on('services');
      $table->foreign('collection_id')->references('id')->on('collections');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('collection_service');
  }
}
