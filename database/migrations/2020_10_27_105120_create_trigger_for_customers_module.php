<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerForCustomersModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_customers AFTER UPDATE ON `users` FOR EACH ROW 
            BEGIN
                DECLARE user_type VARCHAR(200);
                DECLARE dbtable VARCHAR(200);

                DECLARE old_role VARCHAR(200);
                DECLARE new_role VARCHAR(200);

                IF ((OLD.firstname <=> NEW.firstname) = 0) THEN  

                    IF(OLD.role_id <> 3) THEN 
                        INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                        VALUES(NEW.user_id, 'update', 'updated the first name', concat('updated the first name of ',NEW.name,' from ',OLD.firstname,' to ',NEW.firstname), NOW(), 'users', OLD.firstname, NEW.firstname, OLD.id);
                    END IF;

                END IF;

                IF ((OLD.lastname <=> NEW.lastname) = 0) THEN

                    IF(OLD.role_id <> 3) THEN   
                        INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                        VALUES(NEW.user_id, 'update', 'updated the last name', concat('updated the last name of ',NEW.name,' from ',OLD.lastname,' to ',NEW.lastname), NOW(), 'users', OLD.lastname, NEW.lastname, OLD.id);
                    END IF;

                END IF;

                IF ((OLD.email <=> NEW.email) = 0) THEN  

                    IF(OLD.role_id <> 3) THEN 
                        INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                        VALUES(NEW.user_id, 'update', 'updated the email', concat('updated the email of ',NEW.name,' from ',OLD.email,' to ',NEW.email), NOW(), 'users', OLD.email, NEW.email, OLD.id);
                    END IF;

                END IF;

                IF ((OLD.avatar <=> NEW.avatar) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', concat(OLD.name, ' updated his avatar'), concat(OLD.name, ' updated his avatar'), NOW(), 'users', OLD.id);
                END IF;

                IF ((OLD.password <=> NEW.password) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', concat(OLD.name,' updated his password'), concat(OLD.name,' updated his password'), NOW(), 'users', OLD.id);
                END IF;

                IF ((OLD.role_id <=> NEW.role_id) = 0) THEN  

                    SET old_role = (SELECT name FROM role WHERE id = OLD.role_id);
                    SET new_role = (SELECT name FROM role WHERE id = NEW.role_id);

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the user role', concat('updated the user role of ',OLD.name,' from ',old_role,' to ',new_role), NOW(), 'users', OLD.id);
                END IF;

                IF ((OLD.is_active <=> NEW.is_active) = 0) THEN  

                    IF(OLD.role_id = 3) THEN
                        SET user_type = 'customer';
                        SET dbtable   = 'customers';
                    ELSE
                        SET user_type = 'user';
                        SET dbtable   = 'users';
                    END IF;

                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) VALUES(NEW.user_id, 'update', CASE WHEN NEW.is_active = 1 THEN concat('activated a ',user_type) ELSE concat('deactivated a ',user_type) END, CASE WHEN NEW.is_active = 1 THEN concat('activate the ',user_type,' name ',OLD.firstname,' ',OLD.lastname) ELSE concat('deactivate the ',user_type,' name ', OLD.firstname,' ',OLD.lastname) END, NOW(), dbtable, OLD.is_active, NEW.is_active, OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_customers`');
    }
}
