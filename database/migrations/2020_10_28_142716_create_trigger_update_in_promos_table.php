<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_promos AFTER UPDATE ON `promos` FOR EACH ROW 
            BEGIN

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the promo name', concat('updated the promo name ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'promos', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.promo_start <=> NEW.promo_start) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the promo start date', concat('updated the promo start date of ',OLD.name,' from ',OLD.promo_start,' to ',NEW.promo_start), NOW(), 'promos', OLD.promo_start, NEW.promo_start, OLD.id);
                END IF;

                IF ((OLD.promo_end <=> NEW.promo_end) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the promo end date', concat('updated the promo end date of ',OLD.name,' from ',OLD.promo_end,' to ',NEW.promo_end), NOW(), 'promos', OLD.promo_end, NEW.promo_end, OLD.id);
                END IF;

                IF ((OLD.discount <=> NEW.discount) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the promo discount', concat('updated the promo discount of ',OLD.name,' from ',OLD.discount,' to ',NEW.discount), NOW(), 'promos', OLD.discount, NEW.discount, OLD.id);
                END IF;

                IF ((OLD.status <=> NEW.status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the promo status', concat('updated the promo status of ',OLD.name,' from ',OLD.status,' to ',NEW.status), NOW(), 'promos', OLD.status, NEW.status, OLD.id);
                END IF;

                IF ((OLD.deleted_at <=> NEW.deleted_at) = 0) THEN 
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) VALUES(NEW.user_id, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'delete' ELSE 'restore' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'deleted a promo' ELSE 'restore a promo' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN concat('deleted the promo name ',OLD.name) ELSE concat('restores the promo name ', OLD.name) END, NOW(), 'promos', OLD.name, '', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_promos`');
    }
}
