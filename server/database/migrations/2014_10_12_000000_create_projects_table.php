<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateProjectsTable extends Migration
{
  public function up()
  {
    Schema::create('projects', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date')->nullable(true);
      $table->string('search_field')->nullable(true);
      $table->string('name');
      $table->longText('description')->nullable(true);
      $table->longText('observations')->nullable(true);
      $table->string('liaison_full_name')->nullable(true);
      $table->string('liaison_phone')->nullable(true);
      $table->string('liaison_email')->nullable(true);
      $table->string('amount_budgeted')->nullable(true);
      $table->string('advance')->nullable(true);
      $table->longText('balance')->nullable(true);
      $table->boolean('done')->nullable(true)->default(false);
      $table->datetime('done_at')->nullable(true);
      $table->integer('done_by')->nullable(true);
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
    Schema::dropIfExists('projects');
  }
}
