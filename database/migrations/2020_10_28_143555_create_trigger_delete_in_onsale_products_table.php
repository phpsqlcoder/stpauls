<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerDeleteInOnsaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_delete_in_onsale_products AFTER DELETE ON `onsale_products` FOR EACH ROW 
            BEGIN

                DECLARE productname VARCHAR(200);
                DECLARE promoname VARCHAR(200);

                SET productname = (SELECT name FROM products WHERE id = OLD.product_id);
                SET promoname   = (SELECT name FROM promos WHERE id = OLD.promo_id);

                INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference)
                VALUES (OLD.user_id, 'remove', 'removed a product in a promo', concat('removed the product name ',productname,' to the promo name ',promoname), NOW(), 'onsale_products',OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_delete_in_onsale_products`');
    }
}
