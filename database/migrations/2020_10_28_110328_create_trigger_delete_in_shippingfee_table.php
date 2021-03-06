<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerDeleteInShippingfeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_delete_shippingfee AFTER DELETE ON `shippingfees` FOR EACH ROW 
            BEGIN
                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference)
                VALUES (OLD.user_id, 'delete', 'deleted a shipping fee', concat('deleted the shipping fee ',OLD.name), NOW(), 'shippingfees',OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_delete_shippingfee`');
    }
}
