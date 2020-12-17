<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerDeleteInShippingfeeWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_delete_in_shippingfee_weights AFTER DELETE ON `shippingfee_weights` FOR EACH ROW 
            BEGIN

                DECLARE shippingfeename VARCHAR(200);

                SET shippingfeename = (SELECT name FROM shippingfees WHERE id = OLD.shippingfee_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference)
                VALUES (OLD.user_id, 'delete', 'deleted a shipping fee weight rate', concat('deleted the shipping fee weight rate ',OLD.weight,' of shipping fee name ',shippingfeename), NOW(), 'shippingfee_weights',OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_delete_in_shippingfee_weights`');
    }
}
