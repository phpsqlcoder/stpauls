<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPickupDateTimeOnSalesHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->date('pickup_date')->nullable();
            $table->text('pickup_time')->nullable();
            $table->text('payment_option')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecommerce_sales_headers');
    }
}
