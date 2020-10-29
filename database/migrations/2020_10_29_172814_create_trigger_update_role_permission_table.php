<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_role_permission AFTER UPDATE ON `role_permission` FOR EACH ROW 
            BEGIN
                DECLARE actDesc VARCHAR(200);
                
                DECLARE permissionName VARCHAR(200);
                DECLARE roleName VARCHAR(200);

                IF ((OLD.isAllowed <=> NEW.isAllowed) = 0) THEN  
                    SET permissionName = (SELECT name FROM permission WHERE id = OLD.permission_id);
                    SET roleName       = (SELECT name FROM role WHERE id = OLD.role_id);

                    IF(NEW.isAllowed = 1) THEN
                        SET actDesc = concat('set the permission name ',permissionName,' as allowed for user role ',roleName);
                    ELSE
                        SET actDesc = concat('remove the permission name ',permissionName,' from user role ',roleName);
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', actDesc, actDesc, NOW(), 'role_permission', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_role_permission`');
    }
}
