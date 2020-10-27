<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertForEcommerceSalesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_insert_payment AFTER INSERT ON `ecommerce_sales_payments` FOR EACH ROW 
                BEGIN

                    DECLARE order_number VARCHAR(200);
                    DECLARE roleid INTEGER;

                    SET order_number = (SELECT order_number FROM ecommerce_sales_headers WHERE id = NEW.sales_header_id);
                    SET roleid = (SELECT role_id FROM users WHERE id = NEW.user_id);

                    IF(roleid <> 3) THEN
                        INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, new_value, reference)
                        Values (NEW.user_id, 'insert', 'added a payment', concat('added a payment for order # ',order_number,' amounting ',FORMAT(NEW.amount,2)), NOW(), 'ecommerce_sales_payments', FORMAT(NEW.amount,2), NEW.id);
                    END IF;
                END
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_insert_payment`');
    }
}
