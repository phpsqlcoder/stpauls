<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductAdditionalInfoIntoNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_additional_info', function (Blueprint $table) {
            $table->string('authors',250)->nullable()->change();
            $table->string('materials',250)->nullable()->change();
            $table->integer('no_of_pages')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_additional_info', function (Blueprint $table) {
            //
        });
    }
}
