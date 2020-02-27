<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateServicesTable extends Migration
{
  public function up()
  {
    Schema::create('services', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date')->nullable(true);
      $table->string('project_name')->nullable(true);
      $table->integer('project_id');
      $table->longText('detail');
      $table->enum('currency', ["Peso","Dolar"])->nullable(true);
      $table->decimal('amount');
      $table->decimal('collected')->nullable(true);
      $table->decimal('invoiced')->nullable(true);
      $table->date('service_due_date')->nullable();
      $table->integer('service_due_days')->nullable();
      $table->date('collection_due_date')->nullable();
      $table->integer('collection_due_days')->nullable();
      $table->boolean('invoiced_service')->nullable(true);
      $table->boolean('collected_service')->nullable(true);
      $table->boolean('added')->nullable(true);
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
    Schema::dropIfExists('services');
  }
}
