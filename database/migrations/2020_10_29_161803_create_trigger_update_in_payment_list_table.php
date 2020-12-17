<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInPaymentListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_payment_list AFTER UPDATE ON `payment_list` FOR EACH ROW 
            BEGIN
                DECLARE status VARCHAR(200);

                IF ((OLD.is_active <=> NEW.is_active) = 0) THEN  

                    IF(NEW.is_active = 1) THEN
                        SET status = 'active';
                    ELSE
                        SET status = 'inactive';
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', concat('set the payment type ', OLD.name,' as ',status), concat('set the payment type ',OLD.name,' as ',status), NOW(), 'payment_list', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_payment_list`');
    }
}
