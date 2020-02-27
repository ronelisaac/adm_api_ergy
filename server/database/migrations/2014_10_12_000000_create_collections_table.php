<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCollectionsTable extends Migration
{
  public function up()
  {
    Schema::create('collections', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date');
      $table->string('user_search_field')->nullable(true);
      $table->integer('user_id');
      $table->enum('currency', ["Peso","Dolar"])->nullable(true);
      $table->decimal('cash')->nullable(true);
      $table->decimal('total_checks')->nullable(true);
      $table->decimal('debit_card')->nullable(true);
      $table->decimal('credit_card')->nullable(true);
      $table->decimal('bank_deposit')->nullable(true);
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
    Schema::dropIfExists('collections');
  }
}
