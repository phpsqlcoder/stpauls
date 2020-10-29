<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerForCustomerReactivationRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_reactivate_request AFTER UPDATE ON `customers` FOR EACH ROW 
            BEGIN

                IF ((OLD.reactivate_request <=> NEW.reactivate_request) = 0) THEN

                    IF(OLD.reactivate_request = 1) THEN
                        INSERT INTO cms_activity_logs 
                        (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 

                        VALUES(NEW.user_id, 'update', CASE WHEN NEW.is_active = 1 THEN 'approved an account reactivation request' WHEN OLD.is_active = 0 THEN 'disapproved an account reactivation request' END, CASE WHEN NEW.is_active = 1 THEN concat('approved an account reactivation request of customer name ',OLD.firstname,' ',OLD.lastname) WHEN OLD.is_active = 0 THEN concat('disapproved an account reactivation request of customer name ', OLD.firstname,' ',OLD.lastname) END, NOW(), 'customers', OLD.id);
                    END IF;
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
        DB::unprepared('DROP TRIGGER `tr_reactivate_request`');
    }
}
