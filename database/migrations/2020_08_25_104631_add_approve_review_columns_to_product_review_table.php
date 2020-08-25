<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproveReviewColumnsToProductReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_product_review', function (Blueprint $table) {
            $table->integer('customer_id');
            $table->integer('is_approved')->nullable();
            $table->integer('approver')->nullable();
            $table->datetime('approved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_product_review', function (Blueprint $table) {
            //
        });
    }
}
