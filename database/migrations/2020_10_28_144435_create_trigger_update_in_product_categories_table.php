<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_product_categories AFTER UPDATE ON `product_categories` FOR EACH ROW 
            BEGIN

                DECLARE newparent_name VARCHAR(200);
                DECLARE oldparent_name VARCHAR(200);

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product category name', concat('updated the product category name ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'product_categories', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.description <=> NEW.description) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product category description', concat('updated the product category description of ',OLD.name,' from ',OLD.description,' to ',NEW.description), NOW(), 'product_categories', OLD.description, NEW.description, OLD.id);
                END IF;

                IF ((OLD.status <=> NEW.status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product category status', concat('updated the product category status of ',OLD.name,' from ',OLD.status,' to ',NEW.status), NOW(), 'product_categories', OLD.status, NEW.status, OLD.id);
                END IF;

                IF ((OLD.parent_id <=> NEW.parent_id) = 0) THEN  

                    IF(NEW.parent_id = 0) THEN
                        SET newparent_name = 'DEFAULT';
                    END IF;

                    IF(NEW.parent_id > 0) THEN
                        SET newparent_name = (SELECT name FROM product_categories WHERE id = NEW.parent_id);
                    END IF;

                    IF(OLD.parent_id = 0) THEN
                        SET oldparent_name = 'DEFAULT';
                    END IF;

                    IF(OLD.parent_id > 0) THEN
                        SET oldparent_name = (SELECT name FROM product_categories WHERE id = OLD.parent_id);
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product parent category', concat('updated the product parent category of ',OLD.name,' from ',oldparent_name,' to ',newparent_name), NOW(), 'product_categories', oldparent_name, newparent_name, OLD.id);
                END IF;

                IF ((OLD.deleted_at <=> NEW.deleted_at) = 0) THEN 
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) VALUES(NEW.created_by, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'delete' ELSE 'restore' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'deleted a product category' ELSE 'restore a product category' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN concat('deleted the product category name ',OLD.name) ELSE concat('restores the product category name ', OLD.name) END, NOW(), 'product_categories', OLD.name, '', OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_product_categories`');
    }
}
