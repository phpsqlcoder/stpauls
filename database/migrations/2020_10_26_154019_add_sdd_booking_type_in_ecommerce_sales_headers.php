<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSddBookingTypeInEcommerceSalesHeaders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->integer("sdd_booking_type")->nullable();
            $table->string('courier_name',150)->nullable();
            $table->string('rider_name',150)->nullable();
            $table->string('rider_contact_no',150)->nullable();
            $table->string('rider_plate_no',150)->nullable();
            $table->text('rider_link_tracker')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            //
        });
    }
}
