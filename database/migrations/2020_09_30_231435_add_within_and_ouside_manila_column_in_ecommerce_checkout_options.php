<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithinAndOusideManilaColumnInEcommerceCheckoutOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_checkout_options', function (Blueprint $table) {
            $table->integer('within_metro_manila');
            $table->integer('outside_metro_manila');
            $table->decimal('maximum_purchase',16,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_checkout_options', function (Blueprint $table) {
            //
        });
    }
}
