<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_provinces', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('region');
            $table->text('province');
            $table->text('slug')->nullable();
            $table->string('island', 150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_provinces');
    }
}
