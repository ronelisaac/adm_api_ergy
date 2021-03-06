<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTimeSeriesTable extends Migration
{
  public function up()
  {
    Schema::create('time_series', function (Blueprint $table) {
      $table->increments('id');
      $table->date('day')->unique();
      $table->decimal('value');
      $table->longText('description')->nullable(true);
      $table->integer('indicator_id');
      $table->boolean('cancelled')->nullable(true)->default(false);
      $table->datetime('cancelled_at')->nullable(true);
      $table->integer('cancelled_by')->nullable(true);
      $table->boolean('blocked')->nullable(true)->default(false);
      $table->datetime('blocked_at')->nullable(true);
      $table->integer('blocked_by')->nullable(true);
      $table->integer('created_by');
      $table->integer('updated_by');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('time_series');
  }
}
