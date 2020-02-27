<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateAddressesTable extends Migration
{
  public function up()
  {
    Schema::create('addresses', function (Blueprint $table) {
      $table->increments('id');
      $table->longText('address_line')->nullable();
      $table->decimal('long', 10, 7)->nullable();
      $table->decimal('lat', 10, 7)->nullable();
      $table->string('street')->nullable();
      $table->string('street_number')->nullable();
      $table->string('neighborhood')->nullable();
      $table->string('locality', 100)->nullable();
      $table->string('district', 100)->nullable();
      $table->string('country', 100)->nullable();
      $table->string('postal_code')->nullable();
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
    Schema::dropIfExists('addresses');
  }
}
