<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateForEcommerceSalesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_sales_payments AFTER UPDATE ON `ecommerce_sales_payments` FOR EACH ROW 
            BEGIN

                DECLARE orderNumber VARCHAR(200);
 
                IF ((OLD.is_verify <=> NEW.is_verify) = 0) THEN  

                    SET orderNumber = (SELECT order_number FROM ecommerce_sales_headers WHERE id = OLD.sales_header_id);


                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table,reference) VALUES(NEW.user_id, CASE WHEN NEW.is_verify = 1 THEN 'approved' ELSE 'rejected' END, CASE WHEN NEW.is_verify = 1 THEN 'approved a payment' ELSE 'rejected a payment' END, CASE WHEN NEW.is_verify = 1 THEN concat('approved the payment of order # ',orderNumber) ELSE concat('rejected the payment for order # ',orderNumber) END, NOW(), 'ecommerce_sales_headers',OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_sales_payments`');
    }
}
