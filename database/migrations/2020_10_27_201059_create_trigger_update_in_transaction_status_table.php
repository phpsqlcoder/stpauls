<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInTransactionStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_transaction_status AFTER UPDATE ON `transaction_status` FOR EACH ROW 
            BEGIN

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the transaction name', concat('updated the transaction name of ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'transaction_status', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.subject <=> NEW.subject) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the email subject', concat('updated the email subject of transaction name ',OLD.name,' from ',OLD.subject,' to ',NEW.subject), NOW(), 'transaction_status', OLD.subject, NEW.subject, OLD.id);
                END IF;

                IF ((OLD.content <=> NEW.content) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the email content', concat('updated the email content of transaction name ',OLD.name,' from ',OLD.content,' to ',NEW.content), NOW(), 'transaction_status', OLD.content, NEW.content, OLD.id);
                END IF;

                IF ((OLD.status <=> NEW.status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the email status', concat('updated the email status of transaction name ',OLD.name,' from ',OLD.status,' to ',NEW.status), NOW(), 'transaction_status', OLD.status, NEW.status, OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_transaction_status`');
    }
}
