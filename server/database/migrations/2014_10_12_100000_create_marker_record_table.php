<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateMarkerRecordTable extends Migration
{
  public function up()
  {
    Schema::create('marker_record', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('record_id')->unsigned();
      $table->integer('marker_id')->unsigned();
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('record_id')->references('id')->on('records');
      $table->foreign('marker_id')->references('id')->on('markers');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('marker_record');
  }
}
