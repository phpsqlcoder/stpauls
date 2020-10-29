<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_products AFTER UPDATE ON `products` FOR EACH ROW 
            BEGIN
                DECLARE oldCategoryName VARCHAR(200);
                DECLARE newCategoryName VARCHAR(200);

                DECLARE dashActivityFront VARCHAR(200);
                DECLARE actDescFront VARCHAR(200);

                DECLARE dashActivityFeatured VARCHAR(200);
                DECLARE actDescfeatured VARCHAR(200);

                DECLARE dashActivityPickup VARCHAR(200);
                DECLARE actDescPickup VARCHAR(200);

                DECLARE dashActivityRecommended VARCHAR(200);
                DECLARE actDescRecommended VARCHAR(200);
                

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product name', concat('updated the product name ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'products', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.category_id <=> NEW.category_id) = 0) THEN  

                    SET oldCategoryName = (SELECT name FROM product_categories WHERE id = OLD.category_id);
                    SET newCategoryName = (SELECT name FROM product_categories WHERE id = NEW.category_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product category', concat('updated the product category of ',OLD.name,' from ',oldCategoryName,' to ',newCategoryName), NOW(), 'products', oldCategoryName, newCategoryName, OLD.id);
                END IF;

                IF ((OLD.short_description <=> NEW.short_description) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product short description', concat('updated the product short description of ',OLD.name,' from ',OLD.short_description,' to ',NEW.short_description), NOW(), 'products', OLD.short_description, NEW.short_description, OLD.id);
                END IF;

                IF ((OLD.description <=> NEW.description) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product description', concat('updated the product description of ',OLD.name,' from ',OLD.description,' to ',NEW.description), NOW(), 'products', OLD.description, NEW.description, OLD.id);
                END IF;

                IF ((OLD.currency <=> NEW.currency) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product currency', concat('updated the product currency of ',OLD.name,' from ',OLD.currency,' to ',NEW.currency), NOW(), 'products', OLD.currency, NEW.currency, OLD.id);
                END IF;

                IF ((OLD.price <=> NEW.price) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product price', concat('updated the product price of ',OLD.name,' from ',OLD.price,' to ',NEW.price), NOW(), 'products', OLD.price, NEW.price, OLD.id);
                END IF;

                IF ((OLD.size <=> NEW.size) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product size', concat('updated the product size of ',OLD.name,' from ',OLD.size,' to ',NEW.size), NOW(), 'products', OLD.size, NEW.size, OLD.id);
                END IF;

                IF ((OLD.weight <=> NEW.weight) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product weight', concat('updated the product weight of ',OLD.name,' from ',OLD.weight,' grams to ',NEW.weight,' grams'), NOW(), 'products', OLD.weight, NEW.weight, OLD.id);
                END IF;

                IF ((OLD.uom <=> NEW.uom) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product unit of measurement', concat('updated the product unit of measurement of ',OLD.name,' from ',OLD.uom,' to ',NEW.uom), NOW(), 'products', OLD.uom, NEW.uom, OLD.id);
                END IF;

                IF ((OLD.reorder_point <=> NEW.reorder_point) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product reorder point', concat('updated the product reorder point of ',OLD.name,' from ',OLD.reorder_point,' to ',NEW.reorder_point), NOW(), 'products', OLD.reorder_point, NEW.reorder_point, OLD.id);
                END IF;

                IF ((OLD.discount <=> NEW.discount) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product discount', concat('updated the product discount of ',OLD.name,' from ',OLD.discount,' to ',NEW.discount), NOW(), 'products', OLD.discount, NEW.discount, OLD.id);
                END IF;

                IF ((OLD.is_featured <=> NEW.is_featured) = 0) THEN 

                    IF(NEW.is_featured = 1) THEN
                        SET dashActivityFeatured = 'set the product as featured';
                        SET actDescfeatured      = concat('set the product name ',OLD.name,' as featured product');
                    END IF;

                    IF(NEW.is_featured = 0) THEN
                        SET dashActivityFeatured = 'removed the product as featured';
                        SET actDescfeatured      = concat('removed the product name ',OLD.name,' from featured product');
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.created_by, 'update', dashActivityFeatured, actDescfeatured, NOW(), 'products', OLD.id);
                END IF;

                IF ((OLD.for_pickup <=> NEW.for_pickup) = 0) THEN 

                    IF(NEW.for_pickup = 1) THEN
                        SET dashActivityPickup  = 'set the product as allowed for store pickup';
                        SET actDescPickup       = concat('set the product name ',OLD.name,' as allowed for store pickup');;
                    END IF;

                    IF(NEW.for_pickup = '') THEN
                        SET dashActivityPickup  = 'removed the product from allowed for store pickup';
                        SET actDescPickup       = concat('removed the product name ',OLD.name,' from allowed for store pickup');
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.created_by, 'update', dashActivityPickup, actDescPickup, NOW(), 'products', OLD.id);
                END IF;

                IF ((OLD.is_recommended <=> NEW.is_recommended) = 0) THEN 

                    IF(NEW.is_recommended = 1) THEN
                        SET dashActivityRecommended  = 'set the product as recommended product';
                        SET actDescRecommended       = concat('set the product name ',OLD.name,' as recommended product');;
                    END IF;

                    IF(NEW.is_recommended = '') THEN
                        SET dashActivityRecommended  = 'removed the product from recommended products';
                        SET actDescRecommended       = concat('removed the product name ',OLD.name,' from recommended products');
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.created_by, 'update', dashActivityRecommended, actDescRecommended, NOW(), 'products', OLD.id);
                END IF;

                IF ((OLD.isfront <=> NEW.isfront) = 0) THEN 

                    IF(NEW.isfront = 1) THEN
                        SET actDescFront      = concat('placed the product name ',OLD.name,' as featured on home page');
                        SET dashActivityFront = concat('placed the product as featured on home page');
                    END IF;

                    IF(NEW.isfront = 0) THEN
                        SET actDescFront      = concat('removed the product name ',OLD.name,' as featured on home page');
                        SET dashActivityFront = concat('removed the product as featured on home page');
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.created_by, 'update', dashActivityFront, actDescFront, NOW(), 'products', OLD.id);
                END IF;

                IF ((OLD.status <=> NEW.status) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product status', concat('updated the product status of ',OLD.name,' from ',OLD.status,' to ',NEW.status), NOW(), 'products', OLD.status, NEW.status, OLD.id);
                END IF;


                IF ((OLD.meta_title <=> NEW.meta_title) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product meta title', concat('updated the product meta title of ',OLD.name,' from ',OLD.meta_title,' to ',NEW.meta_title), NOW(), 'products', OLD.meta_title, NEW.meta_title, OLD.id);
                END IF;

                IF ((OLD.meta_keyword <=> NEW.meta_keyword) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product meta keyword', concat('updated the product meta keyword of ',OLD.name,' from ',OLD.meta_keyword,' to ',NEW.meta_keyword), NOW(), 'products', OLD.meta_keyword, NEW.meta_keyword, OLD.id);
                END IF;

                IF ((OLD.meta_description <=> NEW.meta_description) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.created_by, 'update', 'updated the product meta description', concat('updated the product meta description of ',OLD.name,' from ',OLD.meta_description,' to ',NEW.meta_description), NOW(), 'products', OLD.meta_description, NEW.meta_description, OLD.id);
                END IF;


                IF ((OLD.deleted_at <=> NEW.deleted_at) = 0) THEN 
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, reference) VALUES(NEW.created_by, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'delete' ELSE 'restore' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'deleted a product' ELSE 'restore a product' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN concat('deleted the product name ',OLD.name) ELSE concat('restores the product name ', OLD.name) END, NOW(), 'products', OLD.name, OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_products`');
    }
}
