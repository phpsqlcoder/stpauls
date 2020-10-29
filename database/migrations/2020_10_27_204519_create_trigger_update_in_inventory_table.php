<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_inventory AFTER UPDATE ON `inventory_receiver_header` FOR EACH ROW 
            BEGIN

                IF ((OLD.status <=> NEW.status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the inventory status', concat('updated the status of inventory reference # ',OLD.id,' from ',OLD.status,' to ',NEW.status), NOW(), 'inventory_receiver_header', OLD.status, NEW.status, OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_inventory`');
    }
}
