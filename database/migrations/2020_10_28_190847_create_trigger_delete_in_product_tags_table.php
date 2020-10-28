<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerDeleteInProductTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_delete_in_product_tags AFTER DELETE ON `product_tags` FOR EACH ROW 
            BEGIN

                DECLARE productName VARCHAR(200);
                SET productName = (SELECT name FROM products WHERE id = OLD.product_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference)
                VALUES (OLD.created_by, 'remove', 'removed a product tag', concat('removed the tag ',OLD.tag,' from product name ',productName), NOW(), 'product_tags',OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_delete_in_product_tags`');
    }
}
