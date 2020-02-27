<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateIndicatorsTable extends Migration
{
  public function up()
  {
    Schema::create('indicators', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('measurement_unit');
      $table->string('field_date');
      $table->string('operation_field');
      $table->string('form_name');
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
    Schema::dropIfExists('indicators');
  }
}
