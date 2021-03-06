<?php

use Illuminate\Database\Seeder;

class TblCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Countries::insert([
            ['name' => 'Hong Kong','slug' => 'hong-kong'],
            ['name' => 'Macau','slug' => 'macau'],
            ['name' => 'Singapore','slug' => 'singapore'],
            ['name' => 'Brunei','slug' => 'brunei'],
            ['name' => 'Indonesia','slug' => 'indonesia'],
            ['name' => 'Malaysia','slug' => 'malaysia'],
            ['name' => 'South Korea','slug' => 'south-korea'],
            ['name' => 'Taiwan','slug' => 'taiwan'],
            ['name' => 'Thailand','slug' => 'thailand'],
            ['name' => 'Japan','slug' => 'japan'],
            ['name' => 'China (South)','slug' => 'china-south'],
            ['name' => 'Australia','slug' => 'australia'],
            ['name' => 'China (Rest of China)','slug' => 'china-rest-of-china'],
            ['name' => 'New Zealand','slug' => 'new-zealand'],
            ['name' => 'North Korea','slug' => 'north-korea'],
            ['name' => 'Vietnam','slug' => 'vietnam'],
            ['name' => 'Bhutan','slug' => 'bhutan'],
            ['name' => 'Cambodia','slug' => 'cambodia'],
            ['name' => 'India','slug' => 'india'],
            ['name' => 'Lao PDR','slug' => 'lao-pdr'],
            ['name' => 'Nepal','slug' => 'nepal'],
            ['name' => 'Alaska','slug' => 'alaska'],
            ['name' => 'Arizona','slug' => 'arizona'],
            ['name' => 'California','slug' => 'california'],
            ['name' => 'Colorado','slug' => 'colorado'],
            ['name' => 'Hawaii','slug' => 'hawaii'],
            ['name' => 'Idaho','slug' => 'idaho'],
            ['name' => 'Illinois','slug' => 'illinois'],
            ['name' => 'Iowa','slug' => 'iowa'],
            ['name' => 'Kansas','slug' => 'kansas'],
            ['name' => 'Minnesota','slug' => 'minnesota'],
            ['name' => 'Missouri','slug' => 'missouri'],
            ['name' => 'Montana','slug' => 'montana'],
            ['name' => 'Nebraska','slug' => 'nebraska'],
            ['name' => 'Nevada','slug' => 'nevada'],
            ['name' => 'New Mexico','slug' => 'new-mexico'],
            ['name' => 'North Dakota','slug' => 'north-dakota'],
            ['name' => 'Oklahoma','slug' => 'oklahoma'],
            ['name' => 'Oregon','slug' => 'oregon'],
            ['name' => 'South Dakota','slug' => 'south-dakota'],
            ['name' => 'Utah','slug' => 'utah'],
            ['name' => 'Washington','slug' => 'washington'],
            ['name' => 'Wyoming','slug' => 'wyoming'],
            ['name' => 'Alabama','slug' => 'alabama'],
            ['name' => 'Arkansas','slug' => 'arkansas'],
            ['name' => 'Canada','slug' => 'canada'],
            ['name' => 'Connecticut','slug' => 'connecticut'],
            ['name' => 'Delaware','slug' => 'delaware'],
            ['name' => 'Florida','slug' => 'florida'],
            ['name' => 'Georgia (Canada)','slug' => 'georgia-canada'],
            ['name' => 'Indiana','slug' => 'indiana'],
            ['name' => 'Kentucky','slug' => 'kentucky'],
            ['name' => 'Louisiana','slug' => 'louisiana'],
            ['name' => 'Maine','slug' => 'maine'],
            ['name' => 'Maryland','slug' => 'maryland'],
            ['name' => 'Massachussetts','slug' => 'massachussetts'],
            ['name' => 'Michigan','slug' => 'michigan'],
            ['name' => 'Mississippi','slug' => 'mississippi'],
            ['name' => 'New Hampshire','slug' => 'new-hampshire'],
            ['name' => 'New Jersey','slug' => 'new-jersey'],
            ['name' => 'New York','slug' => 'new-york'],
            ['name' => 'North Carolina','slug' => 'north-carolina'],
            ['name' => 'Ohio','slug' => 'ohio'],
            ['name' => 'Pennsylvania','slug' => 'pennsylvania'],
            ['name' => 'Rhode Island','slug' => 'rhode-island'],
            ['name' => 'South Carolina','slug' => 'south-carolina'],
            ['name' => 'Tennessee','slug' => 'tennessee'],
            ['name' => 'Texas','slug' => 'texas'],
            ['name' => 'Virginia','slug' => 'virginia'],
            ['name' => 'West Virginia','slug' => 'west-virginia'],
            ['name' => 'Wisconsin','slug' => 'wisconsin'],
            ['name' => 'Andorra','slug' => 'andorra'],
            ['name' => 'Belgium','slug' => 'belgium'],
            ['name' => 'France','slug' => 'france'],
            ['name' => 'Germany','slug' => 'germany'],
            ['name' => 'Italy','slug' => 'italy'],
            ['name' => 'Netherlands','slug' => 'netherlands'],
            ['name' => 'Norway','slug' => 'norway'],
            ['name' => 'United Kingdom','slug' => 'united-kingdom'],
            ['name' => 'Albania','slug' => 'albania'],
            ['name' => 'Armenia','slug' => 'armenia'],
            ['name' => 'Austria','slug' => 'austria'],
            ['name' => 'Azerbvaijan','slug' => 'azerbvaijan'],
            ['name' => 'Bahrain','slug' => 'bahrain'],
            ['name' => 'Bangladesh','slug' => 'bangladesh'],
            ['name' => 'Belarus','slug' => 'belarus'],
            ['name' => 'Boznia &amp; Herzegovena','slug' => 'boznia-andamp-herzegovena'],
            ['name' => 'Bulgaria','slug' => 'bulgaria'],
            ['name' => 'Czech Republic','slug' => 'chech-republic'],
            ['name' => 'Curacao','slug' => 'curacao'],
            ['name' => 'Cyprus','slug' => 'cyprus'],
            ['name' => 'Denmark','slug' => 'denmark'],
            ['name' => 'Estonia','slug' => 'estonia'],
            ['name' => 'Faroe Islands','slug' => 'faroe-islands'],
            ['name' => 'Finland','slug' => 'finland'],
            ['name' => 'Georgia (US)','slug' => 'georgia-us'],
            ['name' => 'Gibraltar','slug' => 'gibraltar'],
            ['name' => 'Greece','slug' => 'greece'],
            ['name' => 'Guernsey','slug' => 'guernsey'],
            ['name' => 'Hungary','slug' => 'hungary'],
            ['name' => 'Iceland','slug' => 'iceland'],
            ['name' => 'Iran','slug' => 'iran'],
            ['name' => 'Ireland','slug' => 'ireland'],
            ['name' => 'Jersey','slug' => 'jersey'],
            ['name' => 'Jordan','slug' => 'jordan'],
            ['name' => 'Kazakhstan','slug' => 'kazakhstan'],
            ['name' => 'Kuwait','slug' => 'kuwait'],
            ['name' => 'Kyrgyzstan','slug' => 'kyrgyzstan'],
            ['name' => 'Latvia','slug' => 'latvia'],
            ['name' => 'Lebanon','slug' => 'lebanon'],
            ['name' => 'Lithuania','slug' => 'lithuania'],
            ['name' => 'Luxembourg','slug' => 'luxembourg'],
            ['name' => 'Macedonia','slug' => 'macedonia'],
            ['name' => 'Maldova','slug' => 'maldova'],
            ['name' => 'Malta','slug' => 'malta'],
            ['name' => 'Oman','slug' => 'oman'],
            ['name' => 'Pakistan','slug' => 'pakistan'],
            ['name' => 'Poland','slug' => 'poland'],
            ['name' => 'Portugal','slug' => 'portugal'],
            ['name' => 'Qatar','slug' => 'qatar'],
            ['name' => 'Romania','slug' => 'romania'],
            ['name' => 'Russian Federation','slug' => 'russian-federation'],
            ['name' => 'Saudi Arabia','slug' => 'saudi-arabia'],
            ['name' => 'Slovakia','slug' => 'slovakia'],
            ['name' => 'Slovenia','slug' => 'slovenia'],
            ['name' => 'Spain','slug' => 'spain'],
            ['name' => 'Sri Lanka','slug' => 'sro-lanka'],
            ['name' => 'Sweden','slug' => 'sweden'],
            ['name' => 'Switzerland','slug' => 'switzerland'],
            ['name' => 'Syria','slug' => 'syria'],
            ['name' => 'Tahiti','slug' => 'tahiti'],
            ['name' => 'Tajikistan','slug' => 'tajikistan'],
            ['name' => 'Turkey','slug' => 'turkey'],
            ['name' => 'Ukraine','slug' => 'ukraine'],
            ['name' => 'United Arab Emirates','slug' => 'united-arab-emirates'],
            ['name' => 'Uzbekistan','slug' => 'uzbekistan'],
            ['name' => 'Yemen','slug' => 'yemen'],
            ['name' => 'Crotia','slug' => 'crotia'],
            ['name' => 'Afghanistan','slug' => 'afghanistan'],
            ['name' => 'Algeria','slug' => 'algeria'],
            ['name' => 'American Sahoa','slug' => 'american-sahoa'],
            ['name' => 'Angola','slug' => 'angola'],
            ['name' => 'Anguilla','slug' => 'anguilla'],
            ['name' => 'Antigua','slug' => 'antigua'],
            ['name' => 'Argenia','slug' => 'argenia'],
            ['name' => 'Aruba','slug' => 'aruba'],
            ['name' => 'Bahamas','slug' => 'bahamas'],
            ['name' => 'Barbados','slug' => 'barbados'],
            ['name' => 'Belize','slug' => 'belize'],
            ['name' => 'Benin','slug' => 'benin'],
            ['name' => 'Bermuda','slug' => 'bermuda'],
            ['name' => 'Bolivia','slug' => 'bolivia'],
            ['name' => 'Bonaire','slug' => 'bonaire'],
            ['name' => 'Botswana','slug' => 'botswana'],
            ['name' => 'Brazil','slug' => 'brazil'],
            ['name' => 'Burkina Faso','slug' => 'burkina-faso'],
            ['name' => 'Burundi','slug' => 'burundi'],
            ['name' => 'Cammeron','slug' => 'cammeron'],
            ['name' => 'Canary Islands','slug' => 'canary-islands'],
            ['name' => 'Cape Verde','slug' => 'cape-verde'],
            ['name' => 'Cayman Islands','slug' => 'cayman-islands'],
            ['name' => 'Central African Rep','slug' => 'central-african-rep'],
            ['name' => 'Chad Chile','slug' => 'chad-chile'],
            ['name' => 'Colombia','slug' => 'colombia'],
            ['name' => 'Comoros','slug' => 'comoros'],
            ['name' => 'Congo','slug' => 'congo'],
            ['name' => 'Cook Islands','slug' => 'cook-islands'],
            ['name' => 'Costa Rica','slug' => 'costa-rica'],
            ['name' => 'Cote D&#39; Ivoire','slug' => 'cote-d-ivoire'],
            ['name' => 'Cuba','slug' => 'cuba'],
            ['name' => 'Djibouti','slug' => 'djibouti'],
            ['name' => 'Dominica','slug' => 'dominica'],
            ['name' => 'Dominican Republic','slug' => 'dominican-republic'],
            ['name' => 'Ecuador','slug' => 'ecuador'],
            ['name' => 'Egypt','slug' => 'egypt'],
            ['name' => 'El Salvador','slug' => 'el-salvador'],
            ['name' => 'Equatorial Guinea','slug' => 'equatorial-guinea'],
            ['name' => 'Eritrea','slug' => 'eritrea'],
            ['name' => 'Ethiopia','slug' => 'ethiopia'],
            ['name' => 'Falkland Islands','slug' => 'falkland-islands'],
            ['name' => 'Fiji','slug' => 'fiji'],
            ['name' => 'French Guiana','slug' => 'french-guiana'],
            ['name' => 'Gabon','slug' => 'gabon'],
            ['name' => 'Gambia','slug' => 'gambia'],
            ['name' => 'Ghana','slug' => 'ghana'],
            ['name' => 'Greenland','slug' => 'greenland'],
            ['name' => 'Grenada','slug' => 'grenada'],
            ['name' => 'Guadaloupe','slug' => 'guadaloupe'],
            ['name' => 'Guam','slug' => 'guam'],
            ['name' => 'Guatemala','slug' => 'guatemala'],
            ['name' => 'Guinea Republic','slug' => 'guinea-republic'],
            ['name' => 'Guineabissau','slug' => 'guineabissau'],
            ['name' => 'Guyana (British)','slug' => 'guyana-british'],
            ['name' => 'Haiti','slug' => 'haiti'],
            ['name' => 'Honduras','slug' => 'honduras'],
            ['name' => 'Iraq','slug' => 'iraq'],
            ['name' => 'Israel','slug' => 'israel'],
            ['name' => 'Jamaica','slug' => 'jamaica'],
            ['name' => 'Kenya','slug' => 'kenya'],
            ['name' => 'Kiribanti','slug' => 'kiribanti'],
            ['name' => 'Lesotho','slug' => 'lesotho'],
            ['name' => 'Liberia','slug' => 'liberia'],
            ['name' => 'Libya','slug' => 'libya'],
            ['name' => 'Madagascar','slug' => 'madagascar'],
            ['name' => 'Malawi','slug' => 'malawi'],
            ['name' => 'Mali','slug' => 'mali'],
            ['name' => 'Marshall Islands','slug' => 'marshall-islands'],
            ['name' => 'Martinique','slug' => 'martinique'],
            ['name' => 'Mauritania','slug' => 'mauritania'],
            ['name' => 'Mauritius','slug' => 'mauritius'],
            ['name' => 'Mexico','slug' => 'mexico'],
            ['name' => 'Montserrat','slug' => 'montserrat'],
            ['name' => 'Morocco','slug' => 'morocco'],
            ['name' => 'Mozambique','slug' => 'mozambique'],
            ['name' => 'Nauru','slug' => 'nauru'],
            ['name' => 'Nevis','slug' => 'nevis'],
            ['name' => 'New Caledonia','slug' => 'new-caledonia'],
            ['name' => 'Nicaragua','slug' => 'nicaragua'],
            ['name' => 'Niger','slug' => 'niger'],
            ['name' => 'Nigeria','slug' => 'nigeria'],
            ['name' => 'Niue','slug' => 'niue'],
            ['name' => 'North Somalia','slug' => 'north-somalia'],
            ['name' => 'Panama','slug' => 'panama'],
            ['name' => 'Papua New Guinea','slug' => 'papua-new-guinea'],
            ['name' => 'Paraguay','slug' => 'paraguay'],
            ['name' => 'Peru','slug' => 'peru'],
            ['name' => 'Puerto Rico','slug' => 'puerto-rico'],
            ['name' => 'Reunion','slug' => 'reunion'],
            ['name' => 'Rwanda','slug' => 'rwanda'],
            ['name' => 'Saipan','slug' => 'saipan'],
            ['name' => 'Samoa','slug' => 'samoa'],
            ['name' => 'Sao Tome &amp; Principe','slug' => 'sao-tome-andamp-principe'],
            ['name' => 'Senegal','slug' => 'senegal'],
            ['name' => 'Seychelles','slug' => 'seychelles'],
            ['name' => 'Sierra Leone','slug' => 'sierra-leone'],
            ['name' => 'Solomon Islands','slug' => 'solomon-islands'],
            ['name' => 'Somalia','slug' => 'somalia'],
            ['name' => 'South Africa','slug' => 'south-africa'],
            ['name' => 'St. Betherlemy','slug' => 'st-betherlemy'],
            ['name' => 'St. Eustatius','slug' => 'st-eustatius'],
            ['name' => 'St. Kittis','slug' => 'st-kittis'],
            ['name' => 'St. Lucia','slug' => 'st-lucia'],
            ['name' => 'St. Martin','slug' => 'st-martin'],
            ['name' => 'Swaziland','slug' => 'swaziland'],
            ['name' => 'Tanzania','slug' => 'tanzania'],
            ['name' => 'Togo','slug' => 'togo'],
            ['name' => 'Tonga','slug' => 'tonga'],
            ['name' => 'Trinidad &amp; Tobago','slug' => 'trinidad-andamp-tobago'],
            ['name' => 'Tunisia','slug' => 'tunisia'],
            ['name' => 'Turkmenistan','slug' => 'turkmenistan'],
            ['name' => 'Turks &amp; Caico Island','slug' => 'turks-andamp-caico-island'],
            ['name' => 'Tuvalu','slug' => 'tuvalu'],
            ['name' => 'Uruguay','slug' => 'uruguay'],
            ['name' => 'Vanautu','slug' => 'vanautu'],
            ['name' => 'Venezuela','slug' => 'venezuela'],
            ['name' => 'Virgin Islands','slug' => 'virgin-islands'],
            ['name' => 'Zambia','slug' => 'zambia'],
            ['name' => 'Zimbabwe','slug' => 'zimbabwe'],
            ['name' => 'Philippines','slug' => 'philippines'],
            ['name' => 'Federated States of Micronesia','slug' => 'federated-states-of-micronesia'],
            ['name' => 'Uganda','slug' => 'uganda']
        ]);
    }
}
