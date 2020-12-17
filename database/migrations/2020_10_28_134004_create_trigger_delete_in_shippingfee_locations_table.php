<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerDeleteInShippingfeeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_delete_in_shippingfee_locations AFTER DELETE ON `shippingfee_locations` FOR EACH ROW 
            BEGIN

                DECLARE shippingfeename VARCHAR(200);

                SET shippingfeename = (SELECT name FROM shippingfees WHERE id = OLD.shippingfee_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference)
                VALUES (OLD.user_id, 'remove', 'removed a shipping fee location', concat('removed the shipping fee location ',OLD.name,' of shipping fee name ',shippingfeename), NOW(), 'shippingfee_locations',OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_delete_in_shippingfee_locations`');
    }
}
