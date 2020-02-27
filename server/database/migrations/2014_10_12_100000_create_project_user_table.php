<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateProjectUserTable extends Migration
{
  public function up()
  {
    Schema::create('project_user', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->integer('project_id')->unsigned();
      $table->decimal('percentage')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('project_id')->references('id')->on('projects');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('project_user');
  }
}
