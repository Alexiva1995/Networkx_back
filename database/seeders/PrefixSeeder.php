<?php

namespace Database\Seeders;

use App\Models\Prefix;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrefixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayPrefix = [
            [
                'pais'       => 'Afghanistan',
                'iso2' => 'af',
            ],
            [
                'pais'       => 'Albania',
                'iso2' => 'al',
            ],
            [
                'pais'       => 'Algeria',
                'iso2' => 'dz',
            ],
            [
                'pais'       => 'American Samoa',
                'iso2' => 'as',
            ],
            [
                'pais'       => 'Andorra',
                'iso2' => 'ad',
            ],
            [
                'pais'       => 'Angola',
                'iso2' => 'ao',
            ],
            [
                'pais'       => 'Anguilla',
                'iso2' => 'ai',
            ],
            [
                'pais'       => 'Antarctica',
                'iso2' => 'aq',
            ],
            [
                'pais'       => 'Antigua and Barbuda',
                'iso2' => 'ag',
            ],
            [
                'pais'       => 'Argentina',
                'iso2' => 'ar',
            ],
            [
                'pais'       => 'Armenia',
                'iso2' => 'am',
            ],
            [
                'pais'       => 'Aruba',
                'iso2' => 'aw',
            ],
            [
                'pais'       => 'Australia',
                'iso2' => 'au',
            ],
            [
                'pais'       => 'Austria',
                'iso2' => 'at',
            ],
            [
                'pais'       => 'Azerbaijan',
                'iso2' => 'az',
            ],
            [
                'pais'       => 'Bahamas',
                'iso2' => 'bs',
            ],
            [
                'pais'       => 'Bahrain',
                'iso2' => 'bh',
            ],
            [
                'pais'       => 'Bangladesh',
                'iso2' => 'bd',
            ],
            [
                'pais'       => 'Barbados',
                'iso2' => 'bb',
            ],
            [
                'pais'       => 'Belarus',
                'iso2' => 'by',
            ],
            [
                'pais'       => 'Belgium',
                'iso2' => 'be',
            ],
            [
                'pais'       => 'Belize',
                'iso2' => 'bz',
            ],
            [
                'pais'       => 'Benin',
                'iso2' => 'bj',
            ],
            [
                'pais'       => 'Bermuda',
                'iso2' => 'bm',
            ],
            [
                'pais'       => 'Bhutan',
                'iso2' => 'bt',
            ],
            [
                'pais'       => 'Bolivia',
                'iso2' => 'bo',
            ],
            [
                'pais'       => 'Bosnia and Herzegovina',
                'iso2' => 'ba',
            ],
            [
                'pais'       => 'Botswana',
                'iso2' => 'bw',
            ],
            [
                'pais'       => 'Brazil',
                'iso2' => 'br',
            ],
            [
                'pais'       => 'British Indian Ocean Territory',
                'iso2' => 'io',
            ],
            [
                'pais'       => 'British Virgin Islands',
                'iso2' => 'vg',
            ],
            [
                'pais'       => 'Brunei',
                'iso2' => 'bn',
            ],
            [
                'pais'       => 'Bulgaria',
                'iso2' => 'bg',
            ],
            [
                'pais'       => 'Burkina Faso',
                'iso2' => 'bf',
            ],
            [
                'pais'       => 'Burundi',
                'iso2' => 'bi',
            ],
            [
                'pais'       => 'Cambodia',
                'iso2' => 'kh',
            ],
            [
                'pais'       => 'Cameroon',
                'iso2' => 'cm',
            ],
            [
                'pais'       => 'Canada',
                'iso2' => 'ca',
            ],
            [
                'pais'       => 'Cape Verde',
                'iso2' => 'cv',
            ],
            [
                'pais'       => 'Cayman Islands',
                'iso2' => 'ky',
            ],
            [
                'pais'       => 'Central African Republic',
                'iso2' => 'cf',
            ],
            [
                'pais'       => 'Chad',
                'iso2' => 'td',
            ],
            [
                'pais'       => 'Chile',
                'iso2' => 'cl',
            ],
            [
                'pais'       => 'China',
                'iso2' => 'cn',
            ],
            [
                'pais'       => 'Christmas Island',
                'iso2' => 'cx',
            ],
            [
                'pais'       => 'Cocos Islands',
                'iso2' => 'cc',
            ],
            [
                'pais'       => 'Colombia',
                'iso2' => 'co',
            ],
            [
                'pais'       => 'Comoros',
                'iso2' => 'km',
            ],
            [
                'pais'       => 'Cook Islands',
                'iso2' => 'ck',
            ],
            [
                'pais'       => 'Costa Rica',
                'iso2' => 'cr',
            ],
            [
                'pais'       => 'Croatia',
                'iso2' => 'hr',
            ],
            [
                'pais'       => 'Cuba',
                'iso2' => 'cu',
            ],
            [
                'pais'       => 'Curacao',
                'iso2' => 'cw',
            ],
            [
                'pais'       => 'Cyprus',
                'iso2' => 'cy',
            ],
            [
                'pais'       => 'Czech Republic',
                'iso2' => 'cz',
            ],
            [
                'pais'       => 'Democratic Republic of the Congo',
                'iso2' => 'cd',
            ],
            [
                'pais'       => 'Denmark',
                'iso2' => 'dk',
            ],
            [
                'pais'       => 'Djibouti',
                'iso2' => 'dj',
            ],
            [
                'pais'       => 'Dominica',
                'iso2' => 'dm',
            ],
            [
                'pais'       => 'Dominican Republic',
                'iso2' => 'do',
            ],
            [
                'pais'       => 'East Timor',
                'iso2' => 'tl',
            ],
            [
                'pais'       => 'Ecuador',
                'iso2' => 'ec',
            ],
            [
                'pais'       => 'Egypt',
                'iso2' => 'eg',
            ],
            [
                'pais'       => 'El Salvador',
                'iso2' => 'sv',
            ],
            [
                'pais'       => 'Equatorial Guinea',
                'iso2' => 'gq',
            ],
            [
                'pais'       => 'Eritrea',
                'iso2' => 'er',
            ],
            [
                'pais'       => 'Estonia',
                'iso2' => 'ee',
            ],
            [
                'pais'       => 'Ethiopia',
                'iso2' => 'et',
            ],
            [
                'pais'       => 'Falkland Islands',
                'iso2' => 'fk',
            ],
            [
                'pais'       => 'Faroe Islands',
                'iso2' => 'fo',
            ],
            [
                'pais'       => 'Fiji',
                'iso2' => 'fj',
            ],
            [
                'pais'       => 'Finland',
                'iso2' => 'fi',
            ],
            [
                'pais'       => 'France',
                'iso2' => 'fr',
            ],
            [
                'pais'       => 'French Polynesia',
                'iso2' => 'pf',
            ],
            [
                'pais'       => 'Gabon',
                'iso2' => 'ga',
            ],
            [
                'pais'       => 'Gambia',
                'iso2' => 'gm',
            ],
            [
                'pais'       => 'Georgia',
                'iso2' => 'ge',
            ],
            [
                'pais'       => 'Germany',
                'iso2' => 'de',
            ],
            [
                'pais'       => 'Ghana',
                'iso2' => 'gh',
            ],
            [
                'pais'       => 'Gibraltar',
                'iso2' => 'gi',
            ],
            [
                'pais'       => 'Greece',
                'iso2' => 'gr',
            ],
            [
                'pais'       => 'Greenland',
                'iso2' => 'gl',
            ],
            [
                'pais'       => 'Grenada',
                'iso2' => 'gd',
            ],
            [
                'pais'       => 'Guam',
                'iso2' => 'gu',
            ],
            [
                'pais'       => 'Guatemala',
                'iso2' => 'gt',
            ],
            [
                'pais'       => 'Guernsey',
                'iso2' => 'gg',
            ],
            [
                'pais'       => 'Guinea',
                'iso2' => 'gn',
            ],
            [
                'pais'       => 'Guinea-Bissau',
                'iso2' => 'gw',
            ],
            [
                'pais'       => 'Guyana',
                'iso2' => 'gy',
            ],
            [
                'pais'       => 'Haiti',
                'iso2' => 'ht',
            ],
            [
                'pais'       => 'Honduras',
                'iso2' => 'hn',
            ],
            [
                'pais'       => 'Hong Kong',
                'iso2' => 'hk',
            ],
            [
                'pais'       => 'Hungary',
                'iso2' => 'hu',
            ],
            [
                'pais'       => 'Iceland',
                'iso2' => 'is',
            ],
            [
                'pais'       => 'India',
                'iso2' => 'in',
            ],
            [
                'pais'       => 'Indonesia',
                'iso2' => 'id',
            ],
            [
                'pais'       => 'Iran',
                'iso2' => 'ir',
            ],
            [
                'pais'       => 'Iraq',
                'iso2' => 'iq',
            ],
            [
                'pais'       => 'Ireland',
                'iso2' => 'ie',
            ],
            [
                'pais'       => 'Isle of Man',
                'iso2' => 'im',
            ],
            [
                'pais'       => 'Israel',
                'iso2' => 'il',
            ],
            [
                'pais'       => 'Italy',
                'iso2' => 'it',
            ],
            [
                'pais'       => 'Ivory Coast',
                'iso2' => 'ci',
            ],
            [
                'pais'       => 'Jamaica',
                'iso2' => 'jm',
            ],
            [
                'pais'       => 'Japan',
                'iso2' => 'jp',
            ],
            [
                'pais'       => 'Jersey',
                'iso2' => 'je',
            ],
            [
                'pais'       => 'Jordan',
                'iso2' => 'jo',
            ],
            [
                'pais'       => 'Kazakhstan',
                'iso2' => 'kz',
            ],
            [
                'pais'       => 'Kenya',
                'iso2' => 'ke',
            ],
            [
                'pais'       => 'Kiribati',
                'iso2' => 'ki',
            ],
            [
                'pais'       => 'Kosovo',
                'iso2' => 'xk',
            ],
            [
                'pais'       => 'Kuwait',
                'iso2' => 'kw',
            ],
            [
                'pais'       => 'Kyrgyzstan',
                'iso2' => 'kg',
            ],
            [
                'pais'       => 'Laos',
                'iso2' => 'la',
            ],
            [
                'pais'       => 'Latvia',
                'iso2' => 'lv',
            ],
            [
                'pais'       => 'Lebanon',
                'iso2' => 'lb',
            ],
            [
                'pais'       => 'Lesotho',
                'iso2' => 'ls',
            ],
            [
                'pais'       => 'Liberia',
                'iso2' => 'lr',
            ],
            [
                'pais'       => 'Libya',
                'iso2' => 'ly',
            ],
            [
                'pais'       => 'Liechtenstein',
                'iso2' => 'li',
            ],
            [
                'pais'       => 'Lithuania',
                'iso2' => 'lt',
            ],
            [
                'pais'       => 'Luxembourg',
                'iso2' => 'lu',
            ],
            [
                'pais'       => 'Macau',
                'iso2' => 'mo',
            ],
            [
                'pais'       => 'North Macedonia',
                'iso2' => 'mk',
            ],
            [
                'pais'       => 'Madagascar',
                'iso2' => 'mg',
            ],
            [
                'pais'       => 'Malawi',
                'iso2' => 'mw',
            ],
            [
                'pais'       => 'Malaysia',
                'iso2' => 'my',
            ],
            [
                'pais'       => 'Maldives',
                'iso2' => 'mv',
            ],
            [
                'pais'       => 'Mali',
                'iso2' => 'ml',
            ],
            [
                'pais'       => 'Malta',
                'iso2' => 'mt',
            ],
            [
                'pais'       => 'Marshall Islands',
                'iso2' => 'mh',
            ],
            [
                'pais'       => 'Mauritania',
                'iso2' => 'mr',
            ],
            [
                'pais'       => 'Mauritius',
                'iso2' => 'mu',
            ],
            [
                'pais'       => 'Mayotte',
                'iso2' => 'yt',
            ],
            [
                'pais'       => 'Mexico',
                'iso2' => 'mx',
            ],
            [
                'pais'       => 'Micronesia',
                'iso2' => 'fm',
            ],
            [
                'pais'       => 'Moldova',
                'iso2' => 'md',
            ],
            [
                'pais'       => 'Monaco',
                'iso2' => 'mc',
            ],
            [
                'pais'       => 'Mongolia',
                'iso2' => 'mn',
            ],
            [
                'pais'       => 'Montenegro',
                'iso2' => 'me',
            ],
            [
                'pais'       => 'Montserrat',
                'iso2' => 'ms',
            ],
            [
                'pais'       => 'Morocco',
                'iso2' => 'ma',
            ],
            [
                'pais'       => 'Mozambique',
                'iso2' => 'mz',
            ],
            [
                'pais'       => 'Myanmar',
                'iso2' => 'mm',
            ],
            [
                'pais'       => 'Namibia',
                'iso2' => 'na',
            ],
            [
                'pais'       => 'Nauru',
                'iso2' => 'nr',
            ],
            [
                'pais'       => 'Nepal',
                'iso2' => 'np',
            ],
            [
                'pais'       => 'Netherlands',
                'iso2' => 'nl',
            ],
            [
                'pais'       => 'Netherlands Antilles',
                'iso2' => 'an',
            ],
            [
                'pais'       => 'New Caledonia',
                'iso2' => 'nc',
            ],
            [
                'pais'       => 'New Zealand',
                'iso2' => 'nz',
            ],
            [
                'pais'       => 'Nicaragua',
                'iso2' => 'ni',
            ],
            [
                'pais'       => 'Niger',
                'iso2' => 'ne',
            ],
            [
                'pais'       => 'Nigeria',
                'iso2' => 'ng',
            ],
            [
                'pais'       => 'Niue',
                'iso2' => 'nu',
            ],
            [
                'pais'       => 'North Korea',
                'iso2' => 'kp',
            ],
            [
                'pais'       => 'Northern Mariana Islands',
                'iso2' => 'mp',
            ],
            [
                'pais'       => 'Norway',
                'iso2' => 'no',
            ],
            [
                'pais'       => 'Oman',
                'iso2' => 'om',
            ],
            [
                'pais'       => 'Pakistan',
                'iso2' => 'pk',
            ],
            [
                'pais'       => 'Palau',
                'iso2' => 'pw',
            ],
            [
                'pais'       => 'Palestine',
                'iso2' => 'ps',
            ],
            [
                'pais'       => 'Panama',
                'iso2' => 'pa',
            ],
            [
                'pais'       => 'Papua New Guinea',
                'iso2' => 'pg',
            ],
            [
                'pais'       => 'Paraguay',
                'iso2' => 'py',
            ],
            [
                'pais'       => 'Peru',
                'iso2' => 'pe',
            ],
            [
                'pais'       => 'Philippines',
                'iso2' => 'ph',
            ],
            [
                'pais'       => 'Pitcairn',
                'iso2' => 'pn',
            ],
            [
                'pais'       => 'Poland',
                'iso2' => 'pl',
            ],
            [
                'pais'       => 'Portugal',
                'iso2' => 'pt',
            ],
            [
                'pais'       => 'Puerto Rico',
                'iso2' => 'pr',
            ],
            [
                'pais'       => 'Qatar',
                'iso2' => 'qa',
            ],
            [
                'pais'       => 'Republic of the Congo',
                'iso2' => 'cg',
            ],
            [
                'pais'       => 'Reunion',
                'iso2' => 're',
            ],
            [
                'pais'       => 'Romania',
                'iso2' => 'ro',
            ],
            [
                'pais'       => 'Russia',
                'iso2' => 'ru',
            ],
            [
                'pais'       => 'Rwanda',
                'iso2' => 'rw',
            ],
            [
                'pais'       => 'Saint Barthelemy',
                'iso2' => 'bl',
            ],
            [
                'pais'       => 'Saint Helena',
                'iso2' => 'sh',
            ],
            [
                'pais'       => 'Saint Kitts and Nevis',
                'iso2' => 'kn',
            ],
            [
                'pais'       => 'Saint Lucia',
                'iso2' => 'lc',
            ],
            [
                'pais'       => 'Saint Martin',
                'iso2' => 'mf',
            ],
            [
                'pais'       => 'Saint Pierre and Miquelon',
                'iso2' => 'pm',
            ],
            [
                'pais'       => 'Saint Vincent and the Grenadines',
                'iso2' => 'vc',
            ],
            [
                'pais'       => 'Samoa',
                'iso2' => 'ws',
            ],
            [
                'pais'       => 'San Marino',
                'iso2' => 'sm',
            ],
            [
                'pais'       => 'Sao Tome and Principe',
                'iso2' => 'st',
            ],
            [
                'pais'       => 'Saudi Arabia',
                'iso2' => 'sa',
            ],
            [
                'pais'       => 'Senegal',
                'iso2' => 'sn',
            ],
            [
                'pais'       => 'Serbia',
                'iso2' => 'rs',
            ],
            [
                'pais'       => 'Seychelles',
                'iso2' => 'sc',
            ],
            [
                'pais'       => 'Sierra Leone',
                'iso2' => 'sl',
            ],
            [
                'pais'       => 'Singapore',
                'iso2' => 'sg',
            ],
            [
                'pais'       => 'Sint Maarten',
                'iso2' => 'sx',
            ],
            [
                'pais'       => 'Slovakia',
                'iso2' => 'sk',
            ],
            [
                'pais'       => 'Slovenia',
                'iso2' => 'si',
            ],
            [
                'pais'       => 'Solomon Islands',
                'iso2' => 'sb',
            ],
            [
                'pais'       => 'Somalia',
                'iso2' => 'so',
            ],
            [
                'pais'       => 'South Africa',
                'iso2' => 'za',
            ],
            [
                'pais'       => 'South Korea',
                'iso2' => 'kr',
            ],
            [
                'pais'       => 'South Sudan',
                'iso2' => 'ss',
            ],
            [
                'pais'       => 'Spain',
                'iso2' => 'es',
            ],
            [
                'pais'       => 'Sri Lanka',
                'iso2' => 'lk',
            ],
            [
                'pais'       => 'Sudan',
                'iso2' => 'sd',
            ],
            [
                'pais'       => 'Suripais',
                'iso2' => 'sr',
            ],
            [
                'pais'       => 'Svalbard and Jan Mayen',
                'iso2' => 'sj',
            ],
            [
                'pais'       => 'Swaziland',
                'iso2' => 'sz',
            ],
            [
                'pais'       => 'Sweden',
                'iso2' => 'se',
            ],
            [
                'pais'       => 'Switzerland',
                'iso2' => 'ch',
            ],
            [
                'pais'       => 'Syria',
                'iso2' => 'sy',
            ],
            [
                'pais'       => 'Taiwan',
                'iso2' => 'tw',
            ],
            [
                'pais'       => 'Tajikistan',
                'iso2' => 'tj',
            ],
            [
                'pais'       => 'Tanzania',
                'iso2' => 'tz',
            ],
            [
                'pais'       => 'Thailand',
                'iso2' => 'th',
            ],
            [
                'pais'       => 'Togo',
                'iso2' => 'tg',
            ],
            [
                'pais'       => 'Tokelau',
                'iso2' => 'tk',
            ],
            [
                'pais'       => 'Tonga',
                'iso2' => 'to',
            ],
            [
                'pais'       => 'Trinidad and Tobago',
                'iso2' => 'tt',
            ],
            [
                'pais'       => 'Tunisia',
                'iso2' => 'tn',
            ],
            [
                'pais'       => 'Turkey',
                'iso2' => 'tr',
            ],
            [
                'pais'       => 'Turkmenistan',
                'iso2' => 'tm',
            ],
            [
                'pais'       => 'Turks and Caicos Islands',
                'iso2' => 'tc',
            ],
            [
                'pais'       => 'Tuvalu',
                'iso2' => 'tv',
            ],
            [
                'pais'       => 'U.S. Virgin Islands',
                'iso2' => 'vi',
            ],
            [
                'pais'       => 'Uganda',
                'iso2' => 'ug',
            ],
            [
                'pais'       => 'Ukraine',
                'iso2' => 'ua',
            ],
            [
                'pais'       => 'United Arab Emirates',
                'iso2' => 'ae',
            ],
            [
                'pais'       => 'United Kingdom',
                'iso2' => 'gb',
            ],
            [
                'pais'       => 'United States',
                'iso2' => 'us',
            ],
            [
                'pais'       => 'Uruguay',
                'iso2' => 'uy',
            ],
            [
                'pais'       => 'Uzbekistan',
                'iso2' => 'uz',
            ],
            [
                'pais'       => 'Vanuatu',
                'iso2' => 'vu',
            ],
            [
                'pais'       => 'Vatican',
                'iso2' => 'va',
            ],
            [
                'pais'       => 'Venezuela',
                'iso2' => 've',
            ],
            [
                'pais'       => 'Vietnam',
                'iso2' => 'vn',
            ],
            [
                'pais'       => 'Wallis and Futuna',
                'iso2' => 'wf',
            ],
            [
                'pais'       => 'Western Sahara',
                'iso2' => 'eh',
            ],
            [
                'pais'       => 'Yemen',
                'iso2' => 'ye',
            ],
            [
                'pais'       => 'Zambia',
                'iso2' => 'zm',
            ],
            [
                'pais'       => 'Zimbabwe',
                'iso2' => 'zw',
            ],
        ];


        // $arrayPrefix =
        // [   
        //     [
        //         'pais' => 'AFG',
        //         'iso2'=> 'AF',
        //         'prefix' => '+93',
        //         'pais' => 'Afganistán'
        //     ],
        //     [
        //         'pais' => 'ALB',
        //         'iso2'=> 'AL',
        //         'prefix' => '+355',
        //         'pais' => 'Albania'
        //     ],
        //     [
        //         'pais' => 'DEU',
        //         'iso2'=> 'DE',
        //         'prefix' => '+49',
        //         'pais' => 'Alemania'
        //     ],
        //     [
        //         'pais' => 'AND',
        //         'iso2'=> 'AD',
        //         'prefix' => '+376',
        //         'pais' => 'Andorra'
        //     ],
        //     [
        //         'pais' => 'AGO',
        //         'iso2'=> 'AO',
        //         'prefix' => '+244',
        //         'pais' => 'Angola'
        //     ],
        //     [
        //         'pais' => 'ATG',
        //         'iso2'=> 'AO',
        //         'prefix' => '+1268',
        //         'pais' => 'Antigua y Barbuda'
        //     ],
        //     [
        //         'pais' => 'SAU',
        //         'iso2'=> 'SA',
        //         'prefix' => '+966',
        //         'pais' => 'Arabia Saudita'
        //     ],
        //     [
        //         'pais' => 'DZA',
        //         'iso2'=> 'DZ',
        //         'prefix' => '+213',
        //         'pais' => 'Argelia'
        //     ],
        //     [
        //         'pais' => 'ARG',
        //         'iso2'=> 'AG',
        //         'prefix' => '+54',
        //         'pais' => 'Argentina'
        //     ],
        //     [
        //         'pais' => 'ARM',
        //         'iso2'=> 'AM',
        //         'prefix' => '+374',
        //         'pais' => 'Armenia'
        //     ],
        //     [
        //         'pais' => 'AUS',
        //         'iso2'=> 'AU',
        //         'prefix' => '+61',
        //         'pais' => 'Australia'
        //     ],
        //     [
        //         'pais' => 'AUT',
        //         'iso2'=> 'AT',
        //         'prefix' => '+43',
        //         'pais' => 'Austria'
        //     ],
        //     [
        //         'pais' => 'AZE',
        //         'iso2'=> 'AZ',
        //         'prefix' => '+994',
        //         'pais' => 'Azerbaiyan'
        //     ],
        //     [
        //         'pais' => 'BHS',
        //         'iso2'=> 'BS',
        //         'prefix' => '+1242',
        //         'pais' => 'Bahamas'
        //     ],
        //     [
        //         'pais' => 'BGD',
        //         'iso2'=> 'BD',
        //         'prefix' => '+880',
        //         'pais' => 'Bangladesh'
        //     ],
        //     [
        //         'pais' => 'BRB',
        //         'iso2'=> 'BB',
        //         'prefix' => '+1246',
        //         'pais' => 'Barbados'
        //     ],
        //     [
        //         'pais' => 'BHR',
        //         'iso2'=> 'BH',
        //         'prefix' => '+973',
        //         'pais' => 'Barein'
        //     ],
        //     [
        //         'pais' => 'BEL',
        //         'iso2'=> 'BE',
        //         'prefix' => '+32',
        //         'pais' => 'Bélgica'
        //     ],
        //     [
        //         'pais' => 'BLZ',
        //         'iso2'=> 'BZ',
        //         'prefix' => '+501',
        //         'pais' => 'Belice'
        //     ],
        //     [
        //         'pais' => 'BEN',
        //         'iso2'=> 'BJ',
        //         'prefix' => '+229',
        //         'pais' => 'Benin'
        //     ],
        //     [
        //         'pais' => 'BLR',
        //         'iso2'=> 'BY',
        //         'prefix' => '+375',
        //         'pais' => 'Bielorrusia'
        //     ],
        //     [
        //         'pais' => 'NLD',
        //         'iso2'=> 'NL',
        //         'prefix' => '+31',
        //         'pais' => 'Países Bajos'
        //     ],
        //     [
        //         'pais' => 'BOL',
        //         'iso2'=> 'BO',
        //         'prefix' => '+591',
        //         'pais' => 'Bolivia'
        //     ],
        //     [
        //         'pais' => 'BIH',
        //         'iso2'=> 'BA',
        //         'prefix' => '+387',
        //         'pais' => 'Bosnia y Herzegovina'
        //     ],
        //     [
        //         'pais' => 'BWA',
        //         'iso2'=> 'BW',
        //         'prefix' => '+267',
        //         'pais' => 'Bosnia y Herzegovina'
        //     ],
        //     [
        //         'pais' => 'BRA',
        //         'iso2'=> 'BR',
        //         'prefix' => '+55',
        //         'pais' => 'Brasil'
        //     ],
        //     [
        //         'pais' => 'BRN',
        //         'iso2'=> 'BN',
        //         'prefix' => '+673',
        //         'pais' => 'Brunei'
        //     ],
        //     [
        //         'pais' => 'BGR',
        //         'iso2'=> 'BG',
        //         'prefix' => '+359',
        //         'pais' => 'Bulgaria'
        //     ],
        //     [
        //         'pais' => 'CPV',
        //         'iso2'=> 'CV',
        //         'prefix' => '+238',
        //         'pais' => 'Cabo Verde'
        //     ],
        //     [
        //         'pais' => 'CMR',
        //         'iso2'=> 'CM',
        //         'prefix' => '+237',
        //         'pais' => 'Camerún'
        //     ],
        //     [
        //         'pais' => 'CAN',
        //         'iso2'=> 'CA',
        //         'prefix' => '+1',
        //         'pais' => 'Canadá'
        //     ],
        //     [
        //         'pais' => 'QAT',
        //         'iso2'=> 'QA',
        //         'prefix' => '+974',
        //         'pais' => 'Qatar'
        //     ], 
        //     [
        //         'pais' => 'CHL',
        //         'iso2'=> 'CL',
        //         'prefix' => '+56' ,
        //         'pais' => 'Chile'
        //     ],
        //     [
        //         'pais' => 'CHN',
        //         'iso2'=> 'CN',
        //         'prefix' => '+86',
        //         'pais' => 'Canadá'
        //     ],
        //     [
        //         'pais' => 'CYP',
        //         'iso2'=> 'CY',
        //         'prefix' => '+86',
        //         'pais' => 'China'
        //     ],
        //     [
        //         'pais' => 'VAT',
        //         'iso2'=> 'VA',
        //         'prefix' => '+39',
        //         'pais'  => 'Ciudad del Vaticano'
                
        //     ],
        //     [
        //         'pais' => 'COL',
        //         'iso2'=> 'CO',
        //         'prefix' => '+57',
        //         'pais' => 'Colombia'
        //     ],
        //     [
        //         'pais' => 'PRK',
        //         'iso2'=> 'KP',
        //         'prefix' => '+850',
        //         'pais' => 'Corea del Norte'
        //     ],
        //     [
        //         'pais' => 'KOR',
        //         'iso2'=> 'KR',
        //         'prefix' => '+82',
        //         'pais' => 'Corea del Sur'
        //     ],
        //     [
        //         'pais' => 'CIV',
        //         'iso2'=> 'CI',
        //         'prefix' => '+225',
        //         'pais' => 'Costa de Marfil'
        //     ],
        //     [
        //         'pais' => 'CRI',
        //         'iso2'=> 'CR',
        //         'prefix' => '+506',
        //         'pais' => 'Costa Rica'
        //     ],
        //     [
        //         'pais' => 'HRV',
        //         'iso2'=> 'HR',
        //         'prefix' => '+385',
        //         'pais' => 'Croacia'
        //     ],
        //     [
        //         'pais' => 'CUB',
        //         'iso2'=> 'CU',
        //         'prefix' => '+53',
        //         'pais' => 'Cuba'
        //     ],
        //     [
        //         'pais' => 'DNK',
        //         'iso2'=> 'DK',
        //         'prefix' => '+45',
        //         'pais' => 'Dinamarca'
        //     ],
        //     [
        //         'pais' => 'DMA',
        //         'iso2'=> 'DM',
        //         'prefix' => '+1767',
        //         'pais' => 'Dominica'
        //     ],
        //     [
        //         'pais' => 'ECU',
        //         'iso2'=> 'EC',
        //         'prefix' => '593',
        //         'pais' => 'Ecuador'
        //     ],
        //     [
        //         'pais' => 'EGY',
        //         'iso2'=> 'EG',
        //         'prefix' => '+20',
        //         'pais' => 'Egipto'
        //     ],
        //     [
        //         'pais' => 'SLV',
        //         'iso2'=> 'SV',
        //         'prefix' => '+503',
        //         'pais' => 'El Salvador'
        //     ],
        //     [
        //         'pais' => 'ARE',
        //         'iso2'=> 'AE',
        //         'prefix' => '+971',
        //         'pais' => 'Emiratos Árabes Unidos'
        //     ],
        //     [
        //         'pais' => 'SVK',
        //         'iso2'=> 'SK',
        //         'prefix' => '+421',
        //         'pais' => 'Eslovaquia'
        //     ],
        //     [
        //         'pais' => 'SVN',
        //         'iso2'=> 'SI',
        //         'prefix' => '+386',
        //         'pais' => 'Eslovenia'
        //     ],
        //     [
        //         'pais' => 'ESP',
        //         'iso2'=> 'ES',
        //         'prefix' =>'+34',
        //         'pais' => 'España'
        //     ],
        //     [
        //         'pais' => 'USA',
        //         'iso2'=> 'US',
        //         'prefix' => '+1',
        //         'pais' => 'Estados Unidos de América'
        //     ],
        //     [
        //         'pais' => 'EST',
        //         'iso2'=> 'EE',
        //         'prefix' => '+372',
        //         'pais' => 'Estonia'
        //     ],
        //     [
        //         'pais' => 'PHL',
        //         'iso2'=> 'PH',
        //         'prefix' => '+63',
        //         'pais' => 'Filipinas'
        //     ],
        //     [
        //         'pais' => 'FIN',
        //         'iso2'=> 'FI',
        //         'prefix' => '+358',
        //         'pais' => 'Finlandia'
        //     ],
        //     [
        //         'pais' => 'FRA',
        //         'iso2'=> 'FR',
        //         'prefix' => '+33',
        //         'pais' => 'Francia'
        //     ],
        //     [
        //         'pais' => 'GHA',
        //         'iso2'=> 'GH',
        //         'prefix' => '+233',
        //         'pais' => 'Ghana'
        //     ],
        //     [
        //         'pais' => 'GRC',
        //         'iso2'=> 'GR',
        //         'prefix' => '+30',
        //         'pais' => 'Grecia'
        //     ],
        //     [
        //         'pais' => 'GTM',
        //         'iso2'=> 'GT',
        //         'prefix' => '+502',
        //         'pais' => 'Guatemala'
        //     ],
        //     [
        //         'pais' => 'HTI',
        //         'iso2'=> 'HT',
        //         'prefix' => '+509',
        //         'pais' => 'Haití'
        //     ],
        //     [
        //         'pais' => 'HND',
        //         'iso2'=> 'HN',
        //         'prefix' => '+504',
        //         'pais'  => 'Honduras'
        //     ],
        //     [
        //         'pais' => 'HUN',
        //         'iso2'=> 'HU',
        //         'prefix' => '+36',
        //         'pais'  => 'Hungría'
        //     ],
        //     [
        //         'pais' => 'IND',
        //         'iso2'=> 'IN',
        //         'prefix' => '+91',
        //         'pais'  => 'India'
        //     ],
        //     [
        //         'pais' => 'IDN',
        //         'iso2'=> 'ID',
        //         'prefix' => '+62',
        //         'pais'  => 'Indonesia'
        //     ],
        //     [
        //         'pais' => 'IRQ',
        //         'iso2'=> 'IQ',
        //         'prefix' => '+964',
        //         'pais'  => 'Irak'
        //     ],
        //     [
        //         'pais' => 'IRN',
        //         'iso2'=> 'IR',
        //         'prefix' => '+98',
        //         'pais'  => 'Irán'
        //     ],
        //     [
        //         'pais' => 'IRL',
        //         'iso2'=> 'IE',
        //         'prefix' => '+353',
        //         'pais'  => 'Irlanda'
        //     ],
        //     [
        //         'pais' => 'ISL',
        //         'iso2'=> 'IS',
        //         'prefix' => '+354',
        //         'pais'  => 'Islandia'
        //     ],
        //     [
        //         'pais' => 'MHL',
        //         'iso2'=> 'MH',
        //         'prefix' => '+692',
        //         'pais'  => 'Islas Marshall'
        //     ],
        //     [
        //         'pais' => 'SLB',
        //         'iso2'=> 'SB',
        //         'prefix' => '+677',
        //         'pais'  => 'Islas Salomón'
        //     ],
        //     [
        //         'pais' => 'ISR',
        //         'iso2'=> 'IL',
        //         'prefix' => '+972',
        //         'pais'  => 'Israel'
        //     ],
        //     [
        //         'pais' => 'ITA',
        //         'iso2'=> 'IT',
        //         'prefix' => '+39',
        //         'pais'  => 'Italia'
        //     ],
        //     [
        //         'pais' => 'JAM',
        //         'iso2'=> 'JM',
        //         'prefix' => '+1876',
        //         'pais'  => 'Jamaica'
        //     ],
        //     [
        //         'pais' => 'JPN',
        //         'iso2'=> 'JP',
        //         'prefix' => '+81'  ,
        //         'pais'  => 'Japón'
        //     ],
           
        //     [
        //         'pais' => 'KAZ',
        //         'iso2'=> 'KZ',
        //         'prefix' => '+7',
        //         'pais'  => 'Kazajistán'
        //     ],
        //     [
        //         'pais' => 'LVA',
        //         'iso2'=> 'LV',
        //         'prefix' => '+371',
        //         'pais'  => 'Letonia'
        //     ],
        //     [
        //         'pais' => 'LBN',
        //         'iso2'=> 'LB',
        //         'prefix' => '+961',
        //         'pais'  => 'Líbano'
        //     ],
        //     [
        //         'pais' => 'LBR',
        //         'iso2'=> 'LR',
        //         'prefix' => '+231',
        //         'pais'  => 'Liberia'
        //     ],
        //     [
        //         'pais' => 'LBY',
        //         'iso2'=> 'LY',
        //         'prefix' => '+218',
        //         'pais'  => 'Libia'
        //     ],
        //     [
        //         'pais' => 'LIE',
        //         'iso2'=> 'LI',
        //         'prefix' => '+423',
        //         'pais'  => 'Liechtenstein'
        //     ],
        //     [
        //         'pais' => 'LTU',
        //         'iso2'=> 'LT',
        //         'prefix' => '+370',
        //         'pais'  => 'Lituania'
        //     ],
        //     [
        //         'pais' => 'LUX',
        //         'iso2'=> 'LU',
        //         'prefix' => '+352',
        //         'pais'  => 'Luxemburgo'
        //     ],
        //     [
        //         'pais' => 'MYS',
        //         'iso2'=> 'MY',
        //         'prefix' => '+60',
        //         'pais'  => 'Malasia'
        //     ],
        //     [
        //         'pais' => 'MDV',
        //         'iso2'=> 'MV',
        //         'prefix' => '+960',
        //         'pais'  => 'Islas Maldivas'
        //     ],
        //     [
        //         'pais' => 'MLI',
        //         'iso2'=> 'ML',
        //         'prefix' => '+223',
        //         'pais'  => 'Mali'
        //     ],
        //     [
        //         'pais' => 'MLT',
        //         'iso2'=> 'MT',
        //         'prefix' => '+356',
        //         'pais'  => 'Malta'
        //     ],
        //     [
        //         'pais' => 'MAR',
        //         'iso2'=> 'MA',
        //         'prefix' => '+212',
        //         'pais'  => 'Marruecos'
        //     ],
        //     [
        //         'pais' => 'MUS',
        //         'iso2'=> 'MU',
        //         'prefix' => '+230',
        //         'pais'  => 'Mauricio'
        //     ],
        //     [
        //         'pais' => 'MRT',
        //         'iso2'=> 'MR',
        //         'prefix' => '+222',
        //         'pais'  => 'Mauritania'
        //     ],
        //     [
        //         'pais' => 'MEX',
        //         'iso2'=> 'MX',
        //         'prefix' => '+52',
        //         'pais'  => 'México'
        //     ],
        //     [
        //         'pais' => 'FSM',
        //         'iso2'=> 'FM',
        //         'prefix' => '+691',
        //         'pais'  => 'Micronesia'
        //     ],
        //     [
        //         'pais' => 'MDA',
        //         'iso2'=> 'MD',
        //         'prefix' => '+373',
        //         'pais'  => 'Moldavia'
        //     ],
        //     [
        //         'pais' => 'MCO',
        //         'iso2'=> 'MC',
        //         'prefix' => '+377',
        //         'pais'  => 'Mónaco'
        //     ],
        //     [
        //         'pais' => 'MNG',
        //         'iso2'=> 'MN',
        //         'prefix' => '+976',
        //         'pais'  => 'Mongolia'
        //     ],
        //     [
        //         'pais' => 'MNE',
        //         'iso2'=> 'ME',
        //         'prefix' => '+382',
        //         'pais'  => 'Montenegro'
        //     ],
        //     [
        //         'pais' => 'MOZ',
        //         'iso2'=> 'MZ',
        //         'prefix' => '+258',
        //         'pais'  => 'Mozambique'
        //     ],
        //     [
        //         'pais' => 'NAM',
        //         'iso2'=> 'NA',
        //         'prefix' => '+264',
        //         'pais'  => 'Namibia'
        //     ],
        //     [
        //         'pais' => 'NRU',
        //         'iso2'=> 'NR',
        //         'prefix' => '+674',
        //         'pais'  => 'Nauru'
        //     ],
        //     [
        //         'pais' => 'NPL',
        //         'iso2'=> 'NP',
        //         'prefix' => '+977',
        //         'pais'  => 'Nepal'
        //     ],
        //     [
        //         'pais' => 'NIC',
        //         'iso2'=> 'NI',
        //         'prefix' => '+505',
        //         'pais'  => 'Nicaragua'
        //     ],
        //     [
        //         'pais' => 'NER',
        //         'iso2'=> 'NE',
        //         'prefix' => '+227',
        //         'pais'  => 'Niger'
        //     ],
        //     [
        //         'pais' => 'NGA',
        //         'iso2'=> 'NG',
        //         'prefix' => '+234',
        //         'pais'  => 'Nigeria'
        //     ],
        //     [
        //         'pais' => 'NOR',
        //         'iso2'=> 'NO',
        //         'prefix' => '+47',
        //         'pais'  => 'Noruega'
        //     ],
        //     [
        //         'pais' => 'NZL',
        //         'iso2'=> 'NZ',
        //         'prefix' => '+64',
        //         'pais'  => 'Nueva Zelanda'
        //     ],
        //     [
        //         'pais' => 'OMN',
        //         'iso2'=> 'OM',
        //         'prefix' => '+968',
        //         'pais'  => 'Omán'
        //     ],
        //     [
        //         'pais' => 'NLD',
        //         'iso2'=> 'NL',
        //         'prefix' => '+31',
        //         'pais'  => 'Países Bajos'
        //     ],
        //     [
        //         'pais' => 'PAK',
        //         'iso2'=> 'PK',
        //         'prefix' => '+92',
        //         'pais'  => 'Pakistán'
        //     ],
        //     [
        //         'pais' => 'PLW',
        //         'iso2'=> 'PW',
        //         'prefix' => '+680',
        //         'pais'  => 'Palau'
        //     ],
        //     [
        //         'pais' => 'PSE',
        //         'iso2'=> 'PS',
        //         'prefix' => '+970',
        //         'pais'  => 'Palestina'
        //     ],
        //     [
        //         'pais' => 'PAN',
        //         'iso2'=> 'PA',
        //         'prefix' => '+507',
        //         'pais'  => 'Panamá'
        //     ],
        //     [
        //         'pais' => 'PNG',
        //         'iso2'=> 'PG',
        //         'prefix' => '+675',
        //         'pais'  => 'Papúa Nueva Guinea'
        //     ],
        //     [
        //         'pais' => 'PRY',
        //         'iso2'=> 'PY',
        //         'prefix' => '+595',
        //         'pais'  => 'Paraguay'
        //     ],
        //     [
        //         'pais' => 'PER',
        //         'iso2'=> 'PE',
        //         'prefix' => '+51',
        //         'pais'  => 'Perú'
        //     ],
        //     [
        //         'pais' => 'POL',
        //         'iso2'=> 'PL',
        //         'prefix' => '+48',
        //         'pais'  => 'Polonia'
        //     ],
        //     [
        //         'pais' => 'PRT',
        //         'iso2'=> 'PT',
        //         'prefix' => '+351',
        //         'pais'  => 'Portugal'
        //     ],
        //     [
        //         'pais' => 'GBR',
        //         'iso2'=> 'GB',
        //         'prefix' => '+44',
        //         'pais'  => 'Reino Unido'
        //     ],
        //     [
        //         'pais' => 'CAF',
        //         'iso2'=> 'CF',
        //         'prefix' => '+236',
        //         'pais'  => 'República Centroafricana'
        //     ],
        //     [
        //         'pais' => 'CZE',
        //         'iso2'=> 'CZ',
        //         'prefix' => '+420',
        //         'pais'  => 'República Checa'
        //     ],
        //     [
        //         'pais' => 'COG',
        //         'iso2'=> 'CG',
        //         'prefix' => '+242',
        //         'pais'  => 'República del Congo'
        //     ],
        //     [
        //         'pais' => 'COD',
        //         'iso2'=> 'CD',
        //         'prefix' => '+243',
        //         'pais'  => 'República Democrática del Congo'
        //     ],
        //     [
        //         'pais' => 'DOM',
        //         'iso2'=> 'DO',
        //         'prefix' => '+1767',
        //         'pais'  => 'Dominica'
        //     ],
        //     [
        //         'pais' => 'REU',
        //         'iso2'=> 'RE',
        //         'prefix' => '+262',
        //         'pais'  => 'Reunión'
        //     ],
        //     [
        //         'pais' => 'RWA',
        //         'iso2'=> 'RW',
        //         'prefix' => '+250',
        //         'pais'  => 'Ruanda'
        //     ],
        //     [
        //         'pais' => 'ROU',
        //         'iso2'=> 'RO',
        //         'prefix' => '+40',
        //         'pais'  => 'Rumanía'
        //     ],
        //     [
        //         'pais' => 'RUS',
        //         'iso2'=> 'RU',
        //         'prefix' => '+7',
        //         'pais'  => 'Rusia'
        //     ],
        //     [
        //         'pais' => 'WSM',
        //         'iso2'=> 'WS',
        //         'prefix' => '+685',
        //         'pais'  => 'Samoa'
        //     ],
        //     [
        //         'pais' => 'KNA',
        //         'iso2'=> 'KN',
        //         'prefix' => '+1869',
        //         'pais'  => 'San Cristóbal y Nieves'
        //     ],
        //     [
        //         'pais' => 'SMR',
        //         'iso2'=> 'SM',
        //         'prefix' => '+378',
        //         'pais'  => 'San Marino'
        //     ],
        //     [
        //         'pais' => 'VCT',
        //         'iso2'=> 'VC',
        //         'prefix' => '+1',
        //         'pais'  => 'Paraguay'
        //     ],
        //     [
        //         'pais' => 'LCA',
        //         'iso2'=> 'LC',
        //         'prefix' => '+1784',
        //         'pais'  => 'San Vicente y las Granadinas'
        //     ],
        //     [
        //         'pais' => 'STP',
        //         'iso2'=> 'ST',
        //         'prefix' => '+239',
        //         'pais'  => 'Santo Tomé y Príncipe'
        //     ],
        //     [
        //         'pais' => 'SEN',
        //         'iso2'=> 'SN',
        //         'prefix' => '+221',
        //         'pais'  => 'Senegal'
        //     ],
        //     [
        //         'pais' => 'SRB',
        //         'iso2'=> 'RS',
        //         'prefix' => '+381',
        //         'pais'  => 'Serbia'
        //     ],
        //     [
        //         'pais' => 'SYC',
        //         'iso2'=> 'SC',
        //         'prefix' => '+248',
        //         'pais'  => 'Seychelles'
        //     ],
        //     [
        //         'pais' => 'SLE',
        //         'iso2'=> 'SL',
        //         'prefix' => '+232',
        //         'pais'  => 'Sierra Leona'
        //     ],
        //     [
        //         'pais' => 'SGP',
        //         'iso2'=> 'SG',
        //         'prefix' => '+65',
        //         'pais'  => 'Singapur'
        //     ],
        //     [
        //         'pais' => 'SYR',
        //         'iso2'=> 'SY',
        //         'prefix' => '+963',
        //         'pais'  => 'Siria'
        //     ],
        //     [
        //         'pais' => 'SOM',
        //         'iso2'=> 'SO',
        //         'prefix' => '+252',
        //         'pais'  => 'Somalia'
        //     ],
        //     [
        //         'pais' => 'LKA',
        //         'iso2'=> 'LK',
        //         'prefix' => '+94',
        //         'pais'  => 'Sri lanka'
        //     ],
        //     [
        //         'pais' => 'SWZ',
        //         'iso2'=> 'SZ',
        //         'prefix' => '+268',
        //         'pais'  => 'Swazilandia'
        //     ],
        //     [
        //         'pais' => 'SDN',
        //         'iso2'=> 'SD',
        //         'prefix' => '+249',
        //         'pais'  => 'Sudán'
        //     ],
        //     [
        //         'pais' => 'SSD',
        //         'iso2'=> 'SS',
        //         'prefix' => '+211',
        //         'pais'  => 'República de Sudán del Sur'
        //     ],
        //     [
        //         'pais' => 'SWE',
        //         'iso2'=> 'SE',
        //         'prefix' => '+46',
        //         'pais'  => 'Suecia'
        //     ],
        //     [
        //         'pais' => 'CHE',
        //         'iso2'=> 'CH',
        //         'prefix' => '+41',
        //         'pais'  => 'Suiza'
        //     ],
        //     [
        //         'pais' => 'SUR',
        //         'iso2'=> 'SR',
        //         'prefix' => '+597',
        //         'pais'  => 'Surinám'
        //     ],
        //     [
        //         'pais' => 'THA',
        //         'iso2'=> 'TZ',
        //         'prefix' => '+66',
        //         'pais'  => 'Tailandia'
        //     ],
        //     [
        //         'pais' => 'TZA',
        //         'iso2'=> 'TZ',
        //         'prefix' => '+255',
        //         'pais'  => 'Tanzania'
        //     ],
        //     [
        //         'pais' => 'TJK',
        //         'iso2'=> 'TJ',
        //         'prefix' => '+992',
        //         'pais'  => 'Tayikistán'
        //     ],
        //     [
        //         'pais' => 'TLS',
        //         'iso2'=> 'TL',
        //         'prefix' => '+670',
        //         'pais'  => 'Timor Oriental'
        //     ],
        //     [
        //         'pais' => 'TGO',
        //         'iso2'=> 'TG',
        //         'prefix' => '+228',
        //         'pais'  => 'Togo'
        //     ],
        //     [
        //         'pais' => 'TON',
        //         'iso2'=> 'TO',
        //         'prefix' => '+676',
        //         'pais'  => 'Tonga'
        //     ],
        //     [
        //         'pais' => 'TTO',
        //         'iso2'=> 'TT',
        //         'prefix' => '+1868',
        //         'pais'  => 'Trinidad y Tobago'
        //     ],
        //     [
        //         'pais' => 'TUN',
        //         'iso2'=> 'TN',
        //         'prefix' => '+216',
        //         'pais'  => 'Tunez'
        //     ],
        //     [
        //         'pais' => 'TKM',
        //         'iso2'=> 'TM',
        //         'prefix' => '+993',
        //         'pais'  => 'Turkmenistán'
        //     ],
        //     [
        //         'pais' => 'TUR',
        //         'iso2'=> 'TR',
        //         'prefix' => '+90',
        //         'pais'  => 'Turquía'
        //     ],
        //     [
        //         'pais' => 'TUV',
        //         'iso2'=> 'TV',
        //         'prefix' => '+688',
        //         'pais'  => 'Tuvalu'
        //     ],
        //     [
        //         'pais' => 'UKR',
        //         'iso2'=> 'UA',
        //         'prefix' => '+380',
        //         'pais'  => 'Ucrania'
        //     ],
        //     [
        //         'pais' => 'UGA',
        //         'iso2'=> 'UG',
        //         'prefix' => '+256',
        //         'pais'  => 'Uganda'
        //     ],
        //     [
        //         'pais' => 'URY',
        //         'iso2'=> 'UY',
        //         'prefix' => '+598',
        //         'pais'  => 'Uruguay'
        //     ],
        //     [
        //         'pais' => 'VEN',
        //         'iso2'=> 'VE',
        //         'prefix' => '+58',
        //         'pais'  => 'Venezuela'
        //     ]
        // ];

        foreach ($arrayPrefix as $prefix ) {
            Prefix::create($prefix);
        }
    }
}
