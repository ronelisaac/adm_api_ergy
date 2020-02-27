<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateUsersTable extends Migration
{
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('password');
      $table->boolean('administrator')->default(false);
      $table->string('link_token')->nullable(true);
      $table->datetime('expiration_date')->nullable(true);
      $table->enum('type', ["Naturales","Jurídicas"])->nullable(true);
      $table->bigInteger('identity_id')->nullable(true);
      $table->bigInteger('tin')->nullable(true);
      $table->string('name');
      $table->string('last_name')->nullable(true);
      $table->date('birth_date')->nullable(true);
      $table->string('email')->nullable(true);
      $table->string('search_field')->nullable(true);
      $table->string('full_name')->nullable(true);
      $table->string('address_line')->nullable(true);
      $table->integer('address_id')->nullable();
      $table->longText('phones')->nullable(true);
      $table->enum('tax_condition', ["Monotributista","Responsable inscripto","Exento"])->nullable(true);
      $table->longText('description')->nullable(true);
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
    Schema::dropIfExists('users');
  }
}
