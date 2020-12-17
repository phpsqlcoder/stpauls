<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER tr_update_branch AFTER UPDATE ON `branches` FOR EACH ROW 
            BEGIN

                IF ((OLD.name <=> NEW.name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the branch name', concat('updated the branch name of ',NEW.name,' from ',OLD.name,' to ',NEW.name), NOW(), 'branches', OLD.name, NEW.name, OLD.id);
                END IF;

                IF ((OLD.address <=> NEW.address) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the branch address', concat('updated the address of branch ',OLD.name,' from ',OLD.address,' to ',NEW.address), NOW(), 'branches', OLD.address, NEW.address, OLD.id);
                END IF;

                IF ((OLD.contact_no <=> NEW.contact_no) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the branch contact number', concat('updated the contact number of branch ',OLD.name,' from ',OLD.contact_no,' to ',NEW.contact_no), NOW(), 'branches', OLD.contact_no, NEW.contact_no, OLD.id);
                END IF;

                IF ((OLD.contact_person <=> NEW.contact_person) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the branch contact person', concat('updated the contact person of branch ',OLD.name,' from ',OLD.contact_person,' to ',NEW.contact_person), NOW(), 'branches', OLD.contact_person, NEW.contact_person, OLD.id);
                END IF;

                IF ((OLD.email <=> NEW.email) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the branch email', concat('updated the email of branch ',OLD.name,' from ',OLD.email,' to ',NEW.email), NOW(), 'branches', OLD.email, NEW.email, OLD.id);
                END IF;

                IF ((OLD.deleted_at <=> NEW.deleted_at) = 0) THEN 
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) VALUES(NEW.user_id, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'delete' ELSE 'restore' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN 'deleted a branch' ELSE 'restore a branch' END, CASE WHEN NEW.deleted_at IS NOT NULL THEN concat('deleted the branch name ',OLD.name) ELSE concat('restores the branch name ', OLD.name) END, NOW(), 'branches', OLD.name, '', OLD.id);
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
        DB::unprepared('DROP TRIGGER `tr_update_branch`');
    }
}
