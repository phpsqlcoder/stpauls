<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateForEcommerceSalesHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_sales AFTER UPDATE ON `ecommerce_sales_headers` FOR EACH ROW 
            BEGIN

                DECLARE order_response VARCHAR(200);
                DECLARE roleid INTEGER;

                /** Payment Status **/ 
                IF ((OLD.payment_status <=> NEW.payment_status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value,reference) VALUES(NEW.user_id, 'update', 'updated the payment status', concat('updated the payment status of order # ',OLD.order_number,' from ',OLD.payment_status,' to ',NEW.payment_status), NOW(), 'ecommerce_sales_headers',OLD.payment_status,NEW.payment_status,OLD.id);
                END IF;

                /** Shipping Fee **/
                IF ((OLD.delivery_fee_amount <=> NEW.delivery_fee_amount) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, new_value,reference) VALUES(NEW.user_id, 'update', 'added a shipping fee', concat('added a shipping fee for order # ',OLD.order_number,' amounting ',FORMAT(NEW.delivery_fee_amount,2)), NOW(), 'ecommerce_sales_headers', FORMAT(NEW.delivery_fee_amount,2),OLD.id);
                END IF; 

                /** Delivery Status **/ 
                IF ((OLD.delivery_status <=> NEW.delivery_status) = 0) THEN  

                    SET roleid = (SELECT role_id FROM users WHERE id = OLD.user_id);

                    IF(roleid <> 3) THEN
                        INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value,reference) VALUES(NEW.user_id, 'update', 'updated the delivery status', concat('updated the delivery status of order # ',OLD.order_number,' from ',OLD.delivery_status,' to ',NEW.delivery_status), NOW(), 'ecommerce_sales_headers',OLD.delivery_status,NEW.delivery_status,OLD.id);
                    END IF;

                END IF;    

                /** COD : Order Response **/ 
                IF ((OLD.is_approve <=> NEW.is_approve) = 0) THEN  

                    IF(NEW.is_approve = 1) THEN
                        SET order_response = 'approved';
                    END IF;

                    IF(NEW.is_approve = 0) THEN
                        SET order_response = 'rejected';
                    END IF;


                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table,reference) VALUES(NEW.user_id, order_response, concat(order_response, ' an order'), concat(order_response, ' the order # ',OLD.order_number), NOW(), 'ecommerce_sales_headers',OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_sales`');
    }
}
