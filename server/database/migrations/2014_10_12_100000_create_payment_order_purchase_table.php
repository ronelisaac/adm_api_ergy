<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePaymentOrderPurchaseTable extends Migration
{
  public function up()
  {
    Schema::create('payment_order_purchase', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('purchase_id')->unsigned();
      $table->integer('payment_order_id')->unsigned();
      $table->string('purchases_search_field')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('purchase_id')->references('id')->on('purchases');
      $table->foreign('payment_order_id')->references('id')->on('payment_orders');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('payment_order_purchase');
  }
}
