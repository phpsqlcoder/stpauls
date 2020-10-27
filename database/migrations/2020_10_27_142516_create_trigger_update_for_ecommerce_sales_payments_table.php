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

                DECLARE payment_response VARCHAR(200);
                DECLARE order_number VARCHAR(200);
                /** COD : Order Response **/ 
                IF ((OLD.is_verify <=> NEW.is_verify) = 0) THEN  

                    IF(NEW.is_verify = 1) THEN
                        SET payment_response = 'approved';
                    END IF;

                    IF(NEW.is_verify = 0) THEN
                        SET payment_response = 'rejected';
                    END IF;

                    SET order_number = (SELECT order_number FROM ecommerce_sales_headers WHERE id = OLD.sales_header_id);


                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table,reference) VALUES(NEW.user_id, payment_response, concat(payment_response, ' a payment'), concat(payment_response, ' the payment for order # ',order_number), NOW(), 'ecommerce_sales_headers',OLD.id);
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
