<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInShippingfeeWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_shippingfee_weights AFTER UPDATE ON `shippingfee_weights` FOR EACH ROW 
            BEGIN
                DECLARE shippingfeename VARCHAR(200);

                IF ((OLD.weight <=> NEW.weight) = 0) THEN

                    SET shippingfeename = (SELECT name FROM shippingfees WHERE id = OLD.shippingfee_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the shipping fee weight', concat('updated the shipping fee weight of ',shippingfeename,' from ',OLD.weight,' to ',NEW.weight), NOW(), 'shippingfee_weights', OLD.weight, NEW.weight, OLD.id);
                END IF;

                IF ((OLD.rate <=> NEW.rate) = 0) THEN
                  
                    SET shippingfeename = (SELECT name FROM shippingfee_weights WHERE id = OLD.shippingfee_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the shipping fee rate', concat('updated the shipping fee rate of ',shippingfeename,' from ',OLD.rate,' to ',NEW.rate), NOW(), 'shippingfee_weights', OLD.rate, NEW.rate, OLD.id);
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
        Schema::dropIfExists('trigger_update_in_shippingfee_weights');
    }
}
