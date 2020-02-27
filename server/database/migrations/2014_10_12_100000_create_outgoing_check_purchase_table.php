<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateOutgoingCheckPurchaseTable extends Migration
{
  public function up()
  {
    Schema::create('outgoing_check_purchase', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('outgoing_check_id')->unsigned();
      $table->integer('purchase_id')->unsigned();
      $table->string('check_search_field')->nullable(true);
      $table->decimal('amount')->nullable(true);
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
      $table->foreign('outgoing_check_id')->references('id')->on('outgoing_checks');
      $table->foreign('purchase_id')->references('id')->on('purchases');
      $table->softDeletes();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('outgoing_check_purchase');
  }
}
