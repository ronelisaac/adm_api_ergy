<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateSaleInvoicesTable extends Migration
{
  public function up()
  {
    Schema::create('sale_invoices', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date');
      $table->date('month')->nullable(true);
      $table->string('user_search_field')->nullable(true);
      $table->integer('user_id');
      $table->decimal('services_total')->nullable(true);
      $table->enum('type', ["FACTURAS A","NOTAS DE DÉBITO A","NOTAS DE CRÉDITO A"])->nullable(true);
      $table->bigInteger('point_of_sale')->nullable(true);
      $table->bigInteger('number')->nullable(true);
      $table->decimal('net_amount')->nullable(true);
      $table->decimal('tax_1')->nullable(true);
      $table->decimal('total');
      $table->longText('observation')->nullable(true);
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
    Schema::dropIfExists('sale_invoices');
  }
}
