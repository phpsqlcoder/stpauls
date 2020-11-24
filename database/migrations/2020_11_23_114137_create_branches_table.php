<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250);
            $table->string('url',250)->nullable();
            $table->string('area',250);
            $table->integer('province_id');
            $table->integer('city_id');
            $table->string('address',250);
            $table->integer('user_id');
            $table->string('status');
            $table->integer('isfeatured');
            $table->string('email');
            $table->text('other_details')->nullable();
            $table->text('img')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
