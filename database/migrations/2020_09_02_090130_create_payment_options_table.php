<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('payment_id');
            $table->string('name',250);
            $table->string('type',250);
            $table->string('account_no',250)->nullable();
            $table->string('branch',250)->nullable();
            $table->string('qrcode',250)->nullable();
            $table->integer('is_default');
            $table->integer('is_active');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_options');
    }
}
