<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitleRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('title_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email',250);
            $table->string('firstname',250);
            $table->string('lastname',250);
            $table->string('mobile_no',250)->nullable();
            $table->text('title');
            $table->string('author',250)->nullable();
            $table->string('isbn',250)->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('title_requests');
    }
}
