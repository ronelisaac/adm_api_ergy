<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePermissionRoleTable extends Migration
{
  public function up()
  {
    Schema::create('permission_role', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('permission_id')->unsigned();
      $table->integer('role_id')->unsigned();
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('permission_id')->references('id')->on('permissions');
      $table->foreign('role_id')->references('id')->on('roles');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('permission_role');
  }
}
