<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateRoleUserTable extends Migration
{
  public function up()
  {
    Schema::create('role_user', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('role_id')->unsigned();
      $table->integer('user_id')->unsigned();
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('role_id')->references('id')->on('roles');
      $table->foreign('user_id')->references('id')->on('users');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('role_user');
  }
}
