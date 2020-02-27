<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateExpensesAccountPurchaseTable extends Migration
{
  public function up()
  {
    Schema::create('expenses_account_purchase', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('expenses_account_id')->unsigned();
      $table->integer('purchase_id')->unsigned();
      $table->decimal('percentage')->nullable(true);
      $table->decimal('amount')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('expenses_account_id')->references('id')->on('expenses_accounts');
      $table->foreign('purchase_id')->references('id')->on('purchases');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('expenses_account_purchase');
  }
}
