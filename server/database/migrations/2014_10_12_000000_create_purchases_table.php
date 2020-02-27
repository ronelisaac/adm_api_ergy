<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePurchasesTable extends Migration
{
  public function up()
  {
    Schema::create('purchases', function (Blueprint $table) {
      $table->increments('id');
      $table->string('search_field')->nullable(true);
      $table->date('date');
      $table->date('month')->nullable(true);
      $table->enum('currency', ["Peso","Dolar"])->nullable(true);
      $table->decimal('exchange_rate')->nullable(true);
      $table->enum('type', ["FACTURAS A","NOTAS DE DÉBITO A","NOTAS DE CRÉDITO A","RECIBOS A","FACTURAS B","NOTAS DE DÉBITO B","NOTAS DE CRÉDITO B","RECIBOS B","FACTURAS C","NOTAS DE DÉBITO C","NOTAS DE CRÉDITO C","RECIBOS C"])->nullable(true);
      $table->bigInteger('point_of_sale')->nullable(true);
      $table->bigInteger('number')->nullable(true);
      $table->string('user_search_field')->nullable(true);
      $table->integer('user_id');
      $table->longText('detail');
      $table->decimal('net_amount');
      $table->decimal('tax_1')->nullable(true);
      $table->decimal('tax_2')->nullable(true);
      $table->decimal('tax_4')->nullable(true);
      $table->decimal('tax_3')->nullable(true);
      $table->decimal('tax_5')->nullable(true);
      $table->decimal('exempt')->nullable(true);
      $table->decimal('untaxed')->nullable(true);
      $table->decimal('internal_tax')->nullable(true);
      $table->decimal('perception_4')->nullable(true);
      $table->decimal('perception_3')->nullable(true);
      $table->decimal('perception_1')->nullable(true);
      $table->decimal('perception_2')->nullable(true);
      $table->decimal('total')->nullable(true);
      $table->decimal('cash')->nullable(true);
      $table->decimal('bank_deposit')->nullable(true);
      $table->decimal('debit_card')->nullable(true);
      $table->decimal('credit_card')->nullable(true);
      $table->decimal('total_checks')->nullable(true);
      $table->decimal('on_credit')->nullable(true);
      $table->decimal('unassigned_amount_projects')->nullable();
      $table->decimal('unassigned_percentage_projects')->nullable();
      $table->decimal('unassigned_amount_expenses_accounts')->nullable();
      $table->decimal('unassigned_percentage_expenses_accounts')->nullable();
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
    Schema::dropIfExists('purchases');
  }
}
