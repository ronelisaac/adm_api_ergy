<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateProductSaleTable extends Migration
{
  public function up()
  {
    Schema::create('product_sale', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('product_id')->unsigned();
      $table->integer('sale_id')->unsigned();
      $table->bigInteger('quantity')->nullable(true)->default(1);
      $table->longText('product_detail')->nullable(true);
      $table->decimal('pesos_price')->nullable(true);
      $table->decimal('dollars_price')->nullable(true);
      $table->decimal('pesos_total')->nullable(true);
      $table->decimal('dollars_total')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('product_id')->references('id')->on('products');
      $table->foreign('sale_id')->references('id')->on('sales');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('product_sale');
  }
}
