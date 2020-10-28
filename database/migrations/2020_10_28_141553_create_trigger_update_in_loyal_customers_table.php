<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInLoyalCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_loyal_customers AFTER UPDATE ON `loyal_customers` FOR EACH ROW 
            BEGIN

                DECLARE newdiscountname VARCHAR(200);
                DECLARE olddiscountname VARCHAR(200);

                IF ((OLD.status <=> NEW.status) = 0) THEN  

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table,reference) VALUES(NEW.user_id, NEW.status, concat(NEW.status, ' a customer loyalty'), concat(NEW.status, ' the customer loyalty of ',OLD.customer_name), NOW(), 'loyal_customers',OLD.id);
                END IF; 

                IF ((OLD.discount_id <=> NEW.discount_id) = 0) THEN  

                    SET newdiscountname = (SELECT name FROM discounts WHERE id = NEW.discount_id);
                    SET olddiscountname = (SELECT name FROM discounts WHERE id = OLD.discount_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table,reference) VALUES(NEW.user_id, 'update', 'updated a customer loyalty discount', concat('updated the customer loyalty discount of ',OLD.customer_name, ' from ',olddiscountname,' to ',newdiscountname), NOW(), 'loyal_customers',OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_loyal_customers`');
    }
}
