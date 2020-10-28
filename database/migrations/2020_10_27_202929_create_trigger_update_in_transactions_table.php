<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_transactions AFTER UPDATE ON `transactions` FOR EACH ROW 
            BEGIN

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the transaction name', concat('updated the transaction name of ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'transactions', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.type <=> NEW.type) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the transaction type', concat('updated the transaction type of ',OLD.name,' from ',OLD.type,' to ',NEW.type), NOW(), 'transactions', OLD.type, NEW.type, OLD.id);
                END IF;

                IF ((OLD.status <=> NEW.status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the transaction status', concat('updated the status of transaction name ',OLD.name,' from ',OLD.status,' to ',NEW.status), NOW(), 'transactions', OLD.status, NEW.status, OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_transactions`');
    }
}
