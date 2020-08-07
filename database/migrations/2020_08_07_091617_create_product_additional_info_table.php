<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAdditionalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_additional_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id');
            $table->text('synopsis')->nullable();
            $table->string('authors',250);
            $table->string('materials', 250);
            $table->integer('no_of_pages');
            $table->string('isbn',250)->nullable();
            $table->text('editorial_reviews')->nullable();
            $table->text('about_author')->nullable();
            $table->text('additional_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_additional_info');
    }
}
