<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInShippingfeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_shippingfee AFTER UPDATE ON `shippingfees` FOR EACH ROW 
            BEGIN

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the shipping fee name', concat('updated the shipping fee name of ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'shippingfees', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.rate <=> NEW.rate) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the shipping fee rate', concat('updated the shipping fee rate of ',OLD.name,' from ',OLD.rate,' to ',NEW.rate), NOW(), 'shippingfees', OLD.rate, NEW.rate, OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_shippingfee`');
    }
}
