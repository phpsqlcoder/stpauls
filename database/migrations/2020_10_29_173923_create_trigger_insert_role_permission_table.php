<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_insert_role_permission AFTER INSERT ON `role_permission` FOR EACH ROW 
            BEGIN

                DECLARE permissionName VARCHAR(200);
                DECLARE roleName VARCHAR(200);

                SET permissionName = (SELECT name FROM permission WHERE id = NEW.permission_id);
                SET roleName       = (SELECT name FROM role WHERE id = NEW.role_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference)
                Values (NEW.user_id, 'insert', concat('added the permission name ',permissionName,' as allowed for user role ',roleName),concat('added the permission name ',permissionName,' as allowed for user role ',roleName), NOW(), 'role_permission',NEW.id);   
                 
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
        DB::unprepared('DROP TRIGGER `trigger_insert_role_permission`');
    }
}
