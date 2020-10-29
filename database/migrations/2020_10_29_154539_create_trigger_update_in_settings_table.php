<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "CREATE TRIGGER trigger_update_in_settings AFTER UPDATE ON `settings` FOR EACH ROW 
            BEGIN

                IF ((OLD.website_name <=> NEW.website_name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the website name', concat('updated the website name from ',OLD.website_name,' to ',NEW.website_name), NOW(), 'settings', OLD.website_name, NEW.website_name, OLD.id);
                END IF;

                IF ((OLD.website_favicon <=> NEW.website_favicon) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the website favicon', concat('updated the website favicon from ',OLD.website_favicon,' to ',NEW.website_favicon), NOW(), 'settings', OLD.website_favicon, NEW.website_favicon, OLD.id);
                END IF;

                IF ((OLD.company_logo <=> NEW.company_logo) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company logo', concat('updated the company logo from ',OLD.company_logo,' to ',NEW.company_logo), NOW(), 'settings', OLD.company_logo, NEW.company_logo, OLD.id);
                END IF;

                IF ((OLD.company_name <=> NEW.company_name) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company name', concat('updated the company name from ',OLD.company_name,' to ',NEW.company_name), NOW(), 'settings', OLD.company_name, NEW.company_name, OLD.id);
                END IF;

                IF ((OLD.company_about <=> NEW.company_about) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company about', concat('updated the company about from ',OLD.company_about,' to ',NEW.company_about), NOW(), 'settings', OLD.company_about, NEW.company_about, OLD.id);
                END IF;

                IF ((OLD.company_address <=> NEW.company_address) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company address', concat('updated the company address from ',OLD.company_address,' to ',NEW.company_address), NOW(), 'settings', OLD.company_address, NEW.company_address, OLD.id);
                END IF;

                IF ((OLD.google_analytics <=> NEW.google_analytics) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the google analytics', concat('updated the google analytics from ',OLD.google_analytics,' to ',NEW.google_analytics), NOW(), 'settings', OLD.google_analytics, NEW.google_analytics, OLD.id);
                END IF;

                IF ((OLD.google_map <=> NEW.google_map) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the google map', concat('updated the google map from ',OLD.google_map,' to ',NEW.google_map), NOW(), 'settings', OLD.google_map, NEW.google_map, OLD.id);
                END IF;

                IF ((OLD.google_recaptcha_sitekey <=> NEW.google_recaptcha_sitekey) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the google recaptcha site key', concat('updated the google recaptcha site key from ',OLD.google_recaptcha_sitekey,' to ',NEW.google_recaptcha_sitekey), NOW(), 'settings', OLD.google_recaptcha_sitekey, NEW.google_recaptcha_sitekey, OLD.id);
                END IF;

                IF ((OLD.google_recaptcha_secret <=> NEW.google_recaptcha_secret) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the google recaptcha secret', concat('updated the google recaptcha secret from ',OLD.google_recaptcha_secret,' to ',NEW.google_recaptcha_secret), NOW(), 'settings', OLD.google_recaptcha_secret, NEW.google_recaptcha_secret, OLD.id);
                END IF;

                IF ((OLD.data_privacy_title <=> NEW.data_privacy_title) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the data privacy title', concat('updated the data privacy title from ',OLD.data_privacy_title,' to ',NEW.data_privacy_title), NOW(), 'settings', OLD.data_privacy_title, NEW.data_privacy_title, OLD.id);
                END IF;

                IF ((OLD.data_privacy_popup_content <=> NEW.data_privacy_popup_content) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the data privacy popup content', concat('updated the data privacy popup content from ',OLD.data_privacy_popup_content,' to ',NEW.data_privacy_popup_content), NOW(), 'settings', OLD.data_privacy_popup_content, NEW.data_privacy_popup_content, OLD.id);
                END IF;

                IF ((OLD.data_privacy_content <=> NEW.data_privacy_content) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the data privacy content', concat('updated the data privacy content from ',OLD.data_privacy_content,' to ',NEW.data_privacy_content), NOW(), 'settings', OLD.data_privacy_content, NEW.data_privacy_content, OLD.id);
                END IF;

                IF ((OLD.mobile_no <=> NEW.mobile_no) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company mobile number', concat('updated the company mobile number from ',OLD.mobile_no,' to ',NEW.mobile_no), NOW(), 'settings', OLD.mobile_no, NEW.mobile_no, OLD.id);
                END IF;

                IF ((OLD.fax_no <=> NEW.fax_no) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company fax number', concat('updated the company fax number from ',OLD.fax_no,' to ',NEW.fax_no), NOW(), 'settings', OLD.fax_no, NEW.fax_no, OLD.id);
                END IF;

                IF ((OLD.tel_no <=> NEW.tel_no) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company telephone number', concat('updated the company telephone number from ',OLD.tel_no,' to ',NEW.tel_no), NOW(), 'settings', OLD.tel_no, NEW.tel_no, OLD.id);
                END IF;

                IF ((OLD.email <=> NEW.email) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company email', concat('updated the company email from ',OLD.email,' to ',NEW.email), NOW(), 'settings', OLD.email, NEW.email, OLD.id);
                END IF;

                IF ((OLD.copyright <=> NEW.copyright) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the company copyright', concat('updated the company copyright from ',OLD.copyright,' to ',NEW.copyright), NOW(), 'settings', OLD.copyright, NEW.copyright, OLD.id);
                END IF;

                IF ((OLD.copyright <=> NEW.copyright) = 0) THEN  
                    INSERT INTO cms_activity_logs (created_by, activity_type, dashboard_activity, activity_desc, activity_date, db_table, old_value, new_value, reference) 
                    VALUES(NEW.user_id, 'update', 'updated the copyright year', concat('updated the copyright year from ',OLD.copyright,' to ',NEW.copyright), NOW(), 'settings', OLD.copyright, NEW.copyright, OLD.id);
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
        DB::unprepared('DROP TRIGGER `trigger_update_in_settings`');
    }
}
