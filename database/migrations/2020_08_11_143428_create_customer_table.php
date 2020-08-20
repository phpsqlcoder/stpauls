<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email',250);
            $table->string('password',250);
            $table->string('firstname',250);
            $table->string('lastname',250);
            $table->string('mobile',250);
            $table->string('telno',250)->nullable();
            $table->text('address');
            $table->text('barangay');
            $table->integer('city');
            $table->integer('province');
            $table->integer('zipcode');
            $table->integer('is_active')->nullable();
            $table->string('provider')->nullable();
            $table->string('fbId')->nullable();
            $table->string('googleId')->nullable();
            $table->integer('is_subscriber')->nullable();
            $table->integer('user_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
