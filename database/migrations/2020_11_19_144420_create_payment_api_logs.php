<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentApiLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_api_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('website_transaction_id_exists');
            $table->string('website_transaction_id', 50);
            $table->string('merchant_transaction_id', 50);
            $table->string('reference_number', 50);
            $table->string('reason_code', 50);
            $table->string('customer_name', 255);
            $table->string('customer_email', 255);
            $table->string('amount', 50);
            $table->text('response_data');
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
        Schema::dropIfExists('payment_api_logs');
    }
}
