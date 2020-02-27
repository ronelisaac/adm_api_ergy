<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateOutgoingChecksTable extends Migration
{
  public function up()
  {
    Schema::create('outgoing_checks', function (Blueprint $table) {
      $table->increments('id');
      $table->string('search_field')->nullable(true);
      $table->string('number')->nullable(true);
      $table->string('bank_name')->nullable(true);
      $table->integer('bank_id');
      $table->enum('type', ["Común","Diferido"])->nullable(true);
      $table->date('date');
      $table->date('due_date')->nullable(true);
      $table->string('user_search_field')->nullable(true);
      $table->integer('user_id');
      $table->decimal('amount');
      $table->longText('description')->nullable(true);
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
    Schema::dropIfExists('outgoing_checks');
  }
}
