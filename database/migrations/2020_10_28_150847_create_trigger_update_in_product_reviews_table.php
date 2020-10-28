<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_product_reviews AFTER UPDATE ON `ecommerce_product_review` FOR EACH ROW 
            BEGIN

                DECLARE customer VARCHAR(200);
                DECLARE product VARCHAR(2000);

                IF ((OLD.is_approved <=> NEW.is_approved) = 0) THEN

                    SET customer = (SELECT firstname, lastname FROM users WHERE id = OLD.customer_id);
                    SET product  = (SELECT name FROM products WHERE id = OLD.product_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.approver, 'approve', 'approved a product review', concat('approved the review of ',customer,' for the product ',product), NOW(), 'ecommerce_product_review', OLD.id);
                END IF;

                IF ((OLD.deleted_at <=> NEW.deleted_at) = 0) THEN 

                    SET product  = (SELECT name FROM products WHERE id = OLD.product_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) VALUES(NEW.user_id, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'delete' ELSE 'restore' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'deleted a product review' ELSE 'restore a product review' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN concat('deleted the product review of product name ',product) ELSE concat('restores the review of product name ', product) END, NOW(), 'ecommerce_product_review', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_product_reviews`');
    }
}
