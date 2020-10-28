<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertInTransactionStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_insert_transaction_status AFTER INSERT ON `transaction_status` FOR EACH ROW 
            BEGIN

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, new_value, reference)
                Values (NEW.user_id, 'insert', 'created a new email template', concat('created the email template name ',NEW.name), NOW(), 'transaction_status', NEW.name,NEW.id);   
                 
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
        DB::unprepared('DROP TRIGGER `tr_insert_transaction_status`');
    }
}
