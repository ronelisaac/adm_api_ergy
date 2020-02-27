<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateSaleFeesTable extends Migration
{
  public function up()
  {
    Schema::create('sale_fees', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date');
      $table->longText('sale_search_field')->nullable(true);
      $table->integer('sale_id');
      $table->bigInteger('fee_number');
      $table->decimal('amount');
      $table->date('due_date');
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
    Schema::dropIfExists('sale_fees');
  }
}
