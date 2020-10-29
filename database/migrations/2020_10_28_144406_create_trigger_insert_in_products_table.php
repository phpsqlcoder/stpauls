<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_insert_in_products AFTER INSERT ON `products` FOR EACH ROW 
            BEGIN

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, new_value, reference)
                Values (NEW.created_by, 'insert', 'created a new product', concat('created the product name ',NEW.name), NOW(), 'products', NEW.name,NEW.id);   
                 
            END"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `trigger_insert_in_products`');
    }
}
