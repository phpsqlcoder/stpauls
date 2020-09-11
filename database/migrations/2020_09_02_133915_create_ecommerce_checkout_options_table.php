<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcommerceCheckoutOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_checkout_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250);
            $table->decimal('delivery_rate',16,2)->nullable();
            $table->decimal('service_fee',16,2)->nullable();
            $table->decimal('minimum_purchase',16,2)->nullable();
            $table->text('allowed_days')->nullable();
            $table->text('allowed_time_from')->nullable();
            $table->text('allowed_time_to')->nullable();
            $table->text('reminder')->nullable();
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
        Schema::dropIfExists('ecommerce_checkout_options');
    }
}
