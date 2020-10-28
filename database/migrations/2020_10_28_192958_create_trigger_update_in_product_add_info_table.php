<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInProductAddInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_product_add_info AFTER UPDATE ON `product_additional_info` FOR EACH ROW 
            BEGIN

                DECLARE productName VARCHAR(200);
                SET productName = (SELECT name FROM products WHERE id = NEW.product_id);

                IF ((OLD.synopsis <=> NEW.synopsis) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product synopsis', concat('updated the synopsis of product name ',productName,' from ',OLD.synopsis,' to ',NEW.synopsis), NOW(), 'product_additional_info', OLD.synopsis, NEW.synopsis, OLD.id);
                END IF;

                IF ((OLD.authors <=> NEW.authors) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product authors', concat('updated the authors of product name ',productName,' from ',OLD.authors,' to ',NEW.authors), NOW(), 'product_additional_info', OLD.authors, NEW.authors, OLD.id);
                END IF;

                IF ((OLD.materials <=> NEW.materials) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product materials', concat('updated the materials of product name ',productName,' from ',OLD.materials,' to ',NEW.materials), NOW(), 'product_additional_info', OLD.materials, NEW.materials, OLD.id);
                END IF;

                IF ((OLD.no_of_pages <=> NEW.no_of_pages) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product number of pages', concat('updated the number of pages of product name ',productName,' from ',OLD.no_of_pages,' to ',NEW.no_of_pages), NOW(), 'product_additional_info', OLD.no_of_pages, NEW.no_of_pages, OLD.id);
                END IF;

                IF ((OLD.isbn <=> NEW.isbn) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product isbn', concat('updated the isbn of product name ',productName,' from ',OLD.isbn,' to ',NEW.isbn), NOW(), 'product_additional_info', OLD.isbn, NEW.isbn, OLD.id);
                END IF;

                IF ((OLD.isbn <=> NEW.isbn) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product isbn', concat('updated the isbn of product name ',productName,' from ',OLD.isbn,' to ',NEW.isbn), NOW(), 'product_additional_info', OLD.isbn, NEW.isbn, OLD.id);
                END IF;

                IF ((OLD.editorial_reviews <=> NEW.editorial_reviews) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product editorial reviews', concat('updated the editorial reviews of product name ',productName,' from ',OLD.editorial_reviews,' to ',NEW.editorial_reviews), NOW(), 'product_additional_info', OLD.editorial_reviews, NEW.editorial_reviews, OLD.id);
                END IF;

                IF ((OLD.about_author <=> NEW.about_author) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product author description', concat('updated the author description of product name ',productName,' from ',OLD.about_author,' to ',NEW.about_author), NOW(), 'product_additional_info', OLD.about_author, NEW.about_author, OLD.id);
                END IF;

                IF ((OLD.additional_info <=> NEW.additional_info) = 0) THEN

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the product additional information', concat('updated the additional information of product name ',productName,' from ',OLD.additional_info,' to ',NEW.additional_info), NOW(), 'product_additional_info', OLD.additional_info, NEW.additional_info, OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_product_add_info`');
    }
}
