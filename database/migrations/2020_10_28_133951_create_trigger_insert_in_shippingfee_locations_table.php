<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertInShippingfeeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_insert_in_shippingfee_locations AFTER INSERT ON `shippingfee_locations` FOR EACH ROW 
            BEGIN

                DECLARE shippingfeename VARCHAR(200);

                SET shippingfeename = (SELECT name FROM shippingfees WHERE id = NEW.shippingfee_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, new_value, reference)
                Values (NEW.user_id, 'insert', 'added a location rate', concat('added the location ',NEW.name, ' to shipping fee name ',shippingfeename), NOW(), 'shippingfee_locations', NEW.name,NEW.id);   
                 
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
        DB::unprepared('DROP TRIGGER `trigger_insert_in_shippingfee_locations`');
    }
}
