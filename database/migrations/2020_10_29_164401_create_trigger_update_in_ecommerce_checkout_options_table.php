<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInEcommerceCheckoutOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_ecommerce_checkout_options AFTER UPDATE ON `ecommerce_checkout_options` FOR EACH ROW 
            BEGIN
                DECLARE insideManilaStatus VARCHAR(200);
                DECLARE outsideManilaStatus VARCHAR(200);

                IF ((OLD.delivery_rate <=> NEW.delivery_rate) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the delivery rate', concat('updated the delivery rate of ',OLD.name,' from ',OLD.delivery_rate,' to ',NEW.delivery_rate), NOW(), 'ecommerce_checkout_options', OLD.delivery_rate, NEW.delivery_rate, OLD.id);
                END IF;

                IF ((OLD.service_fee <=> NEW.service_fee) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the service fee', concat('updated the service fee of ',OLD.name,' from ',OLD.service_fee,' to ',NEW.service_fee), NOW(), 'ecommerce_checkout_options', OLD.service_fee, NEW.service_fee, OLD.id);
                END IF;

                IF ((OLD.minimum_purchase <=> NEW.minimum_purchase) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the minimum purchase', concat('updated the minimum purchase of ',OLD.name,' from ',OLD.minimum_purchase,' to ',NEW.minimum_purchase), NOW(), 'ecommerce_checkout_options', OLD.minimum_purchase, NEW.minimum_purchase, OLD.id);
                END IF;

                IF ((OLD.maximum_purchase <=> NEW.maximum_purchase) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the maximum purchase', concat('updated the maximum purchase of ',OLD.name,' from ',OLD.maximum_purchase,' to ',NEW.maximum_purchase), NOW(), 'ecommerce_checkout_options', OLD.maximum_purchase, NEW.maximum_purchase, OLD.id);
                END IF;

                IF ((OLD.reminder <=> NEW.reminder) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the reminder', concat('updated the reminder of ',OLD.name,' from ',OLD.reminder,' to ',NEW.reminder), NOW(), 'ecommerce_checkout_options', OLD.reminder, NEW.reminder, OLD.id);
                END IF;

                IF ((OLD.allowed_days <=> NEW.allowed_days) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the allowed days', concat('updated the allowed days of ',OLD.name,' from ',OLD.allowed_days,' to ',NEW.allowed_days), NOW(), 'ecommerce_checkout_options', OLD.allowed_days, NEW.allowed_days, OLD.id);
                END IF;

                IF ((OLD.allowed_time_from <=> NEW.allowed_time_from) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the allowed start time', concat('updated the allowed start time of ',OLD.name,' from ',OLD.allowed_time_from,' to ',NEW.allowed_time_from), NOW(), 'ecommerce_checkout_options', OLD.allowed_time_from, NEW.allowed_time_from, OLD.id);
                END IF;

                IF ((OLD.allowed_time_to <=> NEW.allowed_time_to) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the allowed end time', concat('updated the allowed end time of ',OLD.name,' from ',OLD.allowed_time_to,' to ',NEW.allowed_time_to), NOW(), 'ecommerce_checkout_options', OLD.allowed_time_to, NEW.allowed_time_to, OLD.id);
                END IF;

                IF ((OLD.within_metro_manila <=> NEW.within_metro_manila) = 0) THEN 

                    IF(NEW.within_metro_manila = 1) THEN
                        SET insideManilaStatus = 'allowed';
                    ELSE
                        SET insideManilaStatus = 'not allowed';
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', concat('set the Metro Manila delivery as ',insideManilaStatus), concat('set the Metro Manila delivery on ',OLD.name, ' as ',insideManilaStatus), NOW(), 'ecommerce_checkout_options', OLD.id);
                END IF;

                IF ((OLD.outside_metro_manila <=> NEW.outside_metro_manila) = 0) THEN 

                    IF(NEW.outside_metro_manila = 1) THEN
                        SET outsideManilaStatus = 'allowed';
                    ELSE
                        SET outsideManilaStatus = 'not allowed';
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', concat('set the Outside Metro Manila delivery as ',outsideManilaStatus), concat('set the Outside Metro Manila delivery on ',OLD.name, ' as ',outsideManilaStatus), NOW(), 'ecommerce_checkout_options', OLD.id);
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
        Schema::dropIfExists('trigger_update_in_ecommerce_checkout_options');
    }
}
