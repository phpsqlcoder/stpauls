<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverablecitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliverable_cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('province');
            $table->integer('city');
            $table->string('city_name',250);
            $table->decimal('rate',16,2)->default(0);
            $table->integer('is_outside');
            $table->string('status')->default('PRIVATE');
            $table->integer('user_id');
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
        Schema::dropIfExists('deliverable_cities');
    }
}
