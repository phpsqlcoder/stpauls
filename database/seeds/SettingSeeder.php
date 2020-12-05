<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            'id' => 1,
            'api_key' => '',
            'website_name' => 'ST PAULS',
            'website_favicon' => '1602727690_favicon.ico',
            'company_logo' => '1602727690_logo.png',
            'company_favicon' => '',
            'company_name' => 'ST PAULS',
            'company_about' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'company_address' => '7708 St. Paul Road, San Antonio Village 1203 Makati City',
            'google_map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.2714876990763!2d121.05972724792107!3d14.583599997065233!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c869d9acf3bd%3A0x3d08a34bc750b469!2sWebFocus%20Solutions%2C%20Inc.!5e0!3m2!1sen!2sph!4v1568093056927!5m2!1sen!2sph',
            'google_recaptcha_sitekey' => '6LfXBN0ZAAAAAMkVg8iZH_MH3lRRa7d2YvioRoj5',
            'google_recaptcha_secret' => '6LfXBN0ZAAAAAEpLoff6e94HosKbYTbFPGVfOYnd',
            'data_privacy_title' => 'Privacy-Policy',
            'data_privacy_popup_content' => 'This website uses cookies to ensure you get the best experience.',
            'data_privacy_content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            'mobile_no' => '09123456789',
            'fax_no' => '13232107114',
            'tel_no' => '(044) 795-1234',
            'email' => 'support@webfocus.ph',
            'social_media_accounts' => '',
            'copyright' => '2019-2020',
            'user_id' => '1',
            'contact_us_email_layout' => ''
        ];

        DB::table('settings')->insert($setting);
    }
}
