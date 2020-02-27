<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateSaleInvoiceServiceTable extends Migration
{
  public function up()
  {
    Schema::create('sale_invoice_service', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('service_id')->unsigned();
      $table->integer('sale_invoice_id')->unsigned();
      $table->string('name')->nullable(true);
      $table->decimal('amount')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('service_id')->references('id')->on('services');
      $table->foreign('sale_invoice_id')->references('id')->on('sale_invoices');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('sale_invoice_service');
  }
}
