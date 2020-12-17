<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_discounts AFTER UPDATE ON `discounts` FOR EACH ROW 
            BEGIN

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the loyalty discount name', concat('updated the loyalty discount name ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'discounts', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.discount <=> NEW.discount) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the loyalty discount amount', concat('updated the loyalty discount amount of ',OLD.name,' from ',OLD.discount,' to ',NEW.discount), NOW(), 'discounts', OLD.discount, NEW.discount, OLD.id);
                END IF;

                IF ((OLD.status <=> NEW.status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the loyalty discount status', concat('updated the loyalty discount status of ',OLD.name,' from ',OLD.status,' to ',NEW.status), NOW(), 'discounts', OLD.status, NEW.status, OLD.id);
                END IF;

                IF ((OLD.deleted_at <=> NEW.deleted_at) = 0) THEN 
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) VALUES(NEW.user_id, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'delete' ELSE 'restore' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'deleted a loyalty discount' ELSE 'restore a loyalty discount' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN concat('deleted the loyalty discount name ',OLD.name) ELSE concat('restores the loyalty discount name ', OLD.name) END, NOW(), 'discounts', OLD.name, '', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_discounts`');
    }
}
