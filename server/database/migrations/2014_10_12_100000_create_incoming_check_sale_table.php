<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateIncomingCheckSaleTable extends Migration
{
  public function up()
  {
    Schema::create('incoming_check_sale', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('incoming_check_id')->unsigned();
      $table->integer('sale_id')->unsigned();
      $table->string('check_search_field')->nullable(true);
      $table->decimal('amount')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('incoming_check_id')->references('id')->on('incoming_checks');
      $table->foreign('sale_id')->references('id')->on('sales');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('incoming_check_sale');
  }
}
