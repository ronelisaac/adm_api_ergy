<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePermissionsTable extends Migration
{
  public function up()
  {
    Schema::create('permissions', function (Blueprint $table) {
      $table->increments('id');
      $table->string('slug');
      $table->integer('permissions_group_id')->nullable();
      $table->string('name');
      $table->longText('description')->nullable(true);
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
    Schema::dropIfExists('permissions');
  }
}
