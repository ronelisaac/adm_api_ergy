<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateSalesTable extends Migration
{
  public function up()
  {
    Schema::create('sales', function (Blueprint $table) {
      $table->increments('id');
      $table->string('search_field')->nullable(true);
      $table->date('date');
      $table->string('user_search_field')->nullable(true);
      $table->integer('user_id');
      $table->decimal('pesos_subtotal')->nullable(true);
      $table->decimal('dollars_subtotal')->nullable(true);
      $table->decimal('percentage_discount')->nullable(true);
      $table->decimal('pesos_discount')->nullable(true);
      $table->decimal('dollars_discount')->nullable(true);
      $table->decimal('pesos_total')->nullable(true);
      $table->decimal('dollars_total')->nullable(true);
      $table->enum('currency', ["Peso","Dolar"])->nullable(true);
      $table->decimal('cash')->nullable(true);
      $table->decimal('on_credit')->nullable(true);
      $table->decimal('debit_card')->nullable(true);
      $table->decimal('credit_card')->nullable(true);
      $table->decimal('total_checks')->nullable(true);
      $table->decimal('bank_deposit')->nullable(true);
      $table->boolean('invoiced')->nullable(true);
      $table->boolean('collected')->nullable(true);
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
    Schema::dropIfExists('sales');
  }
}
