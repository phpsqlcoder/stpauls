<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInPaymentOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_payment_option AFTER UPDATE ON `payment_options` FOR EACH ROW 
            BEGIN
                DECLARE status VARCHAR(200);

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option name', concat('updated the payment option name ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'payment_options', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.type <=> NEW.type) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option type', concat('updated the payment option type of ',NEW.name,' from ',OLD.type,' to ',NEW.type), NOW(), 'payment_options', OLD.type, NEW.type, OLD.id);
                END IF;

                IF ((OLD.account_no <=> NEW.account_no) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option account number', concat('updated the payment option account number of ',NEW.name,' from ',OLD.account_no,' to ',NEW.account_no), NOW(), 'payment_options', OLD.account_no, NEW.account_no, OLD.id);
                END IF;

                IF ((OLD.branch <=> NEW.branch) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option branch', concat('updated the payment option branch of ',NEW.name,' from ',OLD.branch,' to ',NEW.branch), NOW(), 'payment_options', OLD.branch, NEW.branch, OLD.id);
                END IF;

                IF ((OLD.qrcode <=> NEW.qrcode) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option qr code', concat('updated the payment option qr code of ',NEW.name,' from ',OLD.qrcode,' to ',NEW.qrcode), NOW(), 'payment_options', OLD.qrcode, NEW.qrcode, OLD.id);
                END IF;

                IF ((OLD.qrcode <=> NEW.qrcode) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option qr code', concat('updated the payment option qr code of ',NEW.name,' from ',OLD.qrcode,' to ',NEW.qrcode), NOW(), 'payment_options', OLD.qrcode, NEW.qrcode, OLD.id);
                END IF;

                IF ((OLD.recipient <=> NEW.recipient) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option recipient', concat('updated the payment option recipient of ',NEW.name,' from ',OLD.recipient,' to ',NEW.recipient), NOW(), 'payment_options', OLD.recipient, NEW.recipient, OLD.id);
                END IF;

                IF ((OLD.account_name <=> NEW.account_name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the payment option account name', concat('updated the payment option account name of ',NEW.name,' from ',OLD.account_name,' to ',NEW.account_name), NOW(), 'payment_options', OLD.account_name, NEW.account_name, OLD.id);
                END IF;

                IF ((OLD.is_active <=> NEW.is_active) = 0) THEN  

                    IF(NEW.is_active = 1) THEN
                        SET status = 'active';
                    ELSE
                        SET status = 'inactive';
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', concat('set the payment option ', OLD.name,' as ',status), concat('set the payment option ',OLD.name,' as ',status), NOW(), 'payment_options', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_payment_option`');
    }
}
