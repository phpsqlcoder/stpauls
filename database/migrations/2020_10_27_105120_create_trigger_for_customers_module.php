<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerForCustomersModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_customers AFTER UPDATE ON `users` FOR EACH ROW 
            BEGIN
                        
                IF ((OLD.is_active <=> NEW.is_active) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) VALUES(NEW.user_id, 'update', CASE WHEN NEW.is_active = 1 THEN 'activated a customer' ELSE 'deactivated a customer' END, CASE WHEN NEW.is_active = 1 THEN concat('activate the customer name ',OLD.firstname,' ',OLD.lastname) ELSE concat('deactivate the customer name ', OLD.firstname,' ',OLD.lastname) END, NOW(), 'customers', OLD.is_active, NEW.is_active, OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_customers`');
    }
}
