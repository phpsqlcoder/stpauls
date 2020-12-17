<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_role AFTER UPDATE ON `role` FOR EACH ROW 
            BEGIN

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the role name', concat('updated the role name ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'role', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.description <=> NEW.description) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the role description', concat('updated the role description of ',NEW.name,' from ',OLD.description,' to ',NEW.description), NOW(), 'role', OLD.description, NEW.description, OLD.id);
                END IF;

                IF ((OLD.deleted_at <=> NEW.deleted_at) = 0) THEN 
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) VALUES(NEW.created_by, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'delete' ELSE 'restore' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'deleted a role' ELSE 'restore a role' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN concat('deleted the role name ',OLD.name) ELSE concat('restores the role name ', OLD.name) END, NOW(), 'role', OLD.name, '', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_role`');
    }
}
