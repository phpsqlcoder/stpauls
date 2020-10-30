<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertInProductTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_insert_in_product_tags AFTER INSERT ON `product_tags` FOR EACH ROW 
            BEGIN
                DECLARE productName VARCHAR(200);
                SET productName = (SELECT name FROM products WHERE id = NEW.product_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, new_value, reference)
                Values (NEW.created_by, 'insert', 'added a new product tag', concat('added the tag ',NEW.tag,' on product name ',productName), NOW(), 'product_tags', NEW.tag,NEW.id);   
                 
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
        DB::unprepared('DROP TRIGGER `trigger_insert_in_product_tags`');
    }
}
