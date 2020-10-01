<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOtherColumnsIntoNullableInCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('mobile',250)->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->text('barangay')->nullable()->change();
            $table->integer('city')->nullable()->change();
            $table->integer('province')->nullable()->change();
            $table->integer('zipcode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
