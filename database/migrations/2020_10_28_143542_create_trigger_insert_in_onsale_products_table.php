<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertInOnsaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_insert_in_onsale_products AFTER INSERT ON `onsale_products` FOR EACH ROW 
            BEGIN

                DECLARE productname VARCHAR(200);
                DECLARE promoname VARCHAR(200);

                SET productname = (SELECT name FROM products WHERE id = NEW.product_id);
                SET promoname   = (SELECT name FROM promos WHERE id = NEW.promo_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, new_value, reference)
                Values (NEW.user_id, 'insert', 'added a product to the promo', concat('added the product name ',productname, ' to promo name ',promoname), NOW(), 'onsale_products', productname,NEW.id);   
                 
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
        DB::unprepared('DROP TRIGGER `trigger_insert_in_onsale_products`');
    }
}
