<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInProductPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_product_photos AFTER UPDATE ON `product_photos` FOR EACH ROW 
            BEGIN

                DECLARE productName VARCHAR(200);

                IF ((OLD.name <=> NEW.name) = 0) THEN

                    SET productName = (SELECT name FROM products WHERE id = NEW.product_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product photo', concat('updated the photo of product name ',productName), NOW(), 'product_photos', OLD.id);
                END IF;
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_product_photos`');
    }
}
