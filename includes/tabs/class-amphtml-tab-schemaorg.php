<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if ( ! class_exists( 'AMPHTML_Tab_Schemaorg' ) ) {

    class AMPHTML_Tab_Schemaorg extends AMPHTML_Tab_Abstract {

        public function get_fields() {
            $fields = array(
                array(
                    'id'                    => 'default_logo',
                    'title'                 => esc_html__( 'Publisher Logo', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_default_logo' ),
                    'display_callback_args' => array( 'id' => 'default_logo' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'default_image',
                    'title'                 => esc_html__( 'Default Image', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_default_image' ),
                    'display_callback_args' => array( 'id' => 'default_image' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'schema_type',
                    'title'                 => esc_html__( 'Content Type', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_select' ),
                    'default'               => 'NewsArticle',
                    'display_callback_args' => array(
                        'id'             => 'schema_type',
                        'select_options' => array(
                            'NewsArticle'  => 'NewsArticle',
                            'BlogPosting'  => 'BlogPosting',
                            'LegalService' => 'LegalService'
                        )
                    ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_name',
                    'title'                 => esc_html__( 'Name', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_name', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_telephone',
                    'title'                 => esc_html__( 'Telephone', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_telephone' ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_price_range',
                    'title'                 => esc_html__( 'Price Range', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_price_range', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_street_address',
                    'title'                 => esc_html__( 'Street Address', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_street_address', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_address_locality',
                    'title'                 => esc_html__( 'Address Locality', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_address_locality', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_postal_code',
                    'title'                 => esc_html__( 'Postal Code', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_postal_code', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_address_region',
                    'title'                 => esc_html__( 'Region', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_address_region', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_address_country',
                    'title'                 => esc_html__( 'Country', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_select' ),
                    'display_callback_args' => array(
                        'id'             => 'legal_service_address_country',
                        'select_options' => $this->get_countries(),
                    ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_open_days',
                    'title'                 => esc_html__( 'Day Of Week', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_multiple_select' ),
                    'display_callback_args' => array(
                        'id'             => 'legal_service_open_days',
                        'select_options' => array(
                            'Monday'    => 'Monday',
                            'Tuesday'   => 'Tuesday',
                            'Wednesday' => 'Wednesday',
                            'Thursday'  => 'Thursday',
                            'Friday'    => 'Friday',
                            'Saturday'  => 'Saturday',
                            'Sunday'    => 'Sunday'
                        )
                    ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_opens',
                    'title'                 => esc_html__( 'Opens', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_time_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_opens', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'legal_service_closes',
                    'title'                 => esc_html__( 'Closes', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_time_field' ),
                    'display_callback_args' => array( 'id' => 'legal_service_closes', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'contact_point_contact_type',
                    'title'                 => esc_html__( 'Contact Type', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_select' ),
                    'default'               => 'customer support',
                    'display_callback_args' => array(
                        'id'             => 'contact_point_contact_type',
                        'select_options' => array(
                            'customer support'    => 'Customer Support',
                            'technical support'   => 'Technical Support',
                            'billing support'     => 'Billing Support',
                            'bill payment'        => 'Bill payment',
                            'sales'               => 'Sales',
                            'reservations'        => 'Reservations',
                            'credit card support' => 'Credit Card Support',
                            'emergency'           => 'Emergency',
                            'baggage tracking'    => 'Baggage Tracking',
                            'roadside assistance' => 'Roadside Assistance'
                        )
                    ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'contact_point_telephone',
                    'title'                 => esc_html__( 'Contact Point Telephone', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'contact_point_telephone', 'required' => true ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'contact_point_page_url',
                    'title'                 => esc_html__( 'Contact Point Page URL', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'contact_point_page_url' ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'contact_point_email',
                    'title'                 => esc_html__( 'Contact Point Email', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'contact_point_email' ),
                    'default'               => '',
                    'description'           => '',
                ),
                array(
                    'id'                    => 'contact_point_area_served',
                    'title'                 => esc_html__( 'Contact Point Area Served', 'amphtml' ),
                    'default'               => '',
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'contact_point_area_served' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'contact_point_available_language',
                    'title'                 => esc_html__( 'Contact Point Available Language', 'amphtml' ),
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'default'               => '',
                    'display_callback_args' => array( 'id' => 'contact_point_available_language' ),
                    'description'           => '',
                )
            );

            return apply_filters( 'amphtml_schemaorg_tab_fields', $fields, $this );
        }

        public function display_default_logo( $args ) {
            $id      = $args[ 'id' ];
            $img_url = $this->get_img_url_by_option( $id );
            ?>
            <label for="upload_image">
                <p class="logo_preview" <?php
                if ( ! $img_url ): echo 'style="display:none"';
                endif;
                ?>>
                    <img src="<?php echo esc_url( $img_url ); ?>"
                         alt="<?php esc_html_e( 'Default Logo', 'amphtml' ) ?>"
                         style="width: auto; height: 100px">
                </p>
                <input class="upload_image" type="hidden" name="<?php echo $this->options->get( $id, 'name' ) ?>"
                       value="<?php echo esc_url( $img_url ); ?>"/>
                <input class="button upload_image_button" type="button"
                       value="<?php esc_html_e( 'Upload Image', 'amphtml' ); ?>"/>
                <input <?php
                if ( ! $this->options->get( $id ) ): echo 'style="display:none"';
                endif;
                ?>
                    class="button reset_image_button" type="button" value="<?php esc_html_e( 'Reset Image', 'amphtml' ); ?>"/>
                <p><?php esc_html_e( 'This image is required for Schema.org markup. The logo should fit in a 60x600px rectangle, and either be exactly 60px high or exactly 600px wide.', 'amphtml' ) ?>
                    <a href="https://developers.google.com/search/docs/data-types/articles#logo-guidelines"
                       target="_blank"><?php esc_html_e( 'See full requirements.', 'amphtml' ) ?></a></p>
            </label>
            <?php
        }

        public function display_time_field( $args, $type = 'time' ) {
            $id = $args[ 'id' ];
            ?>
            <p>
                <input style="width: 28%" type="<?php echo $type; ?>"
                       name="<?php echo $this->options->get( $id, 'name' ) ?>"
                       id="<?php echo $id ?>"
                       value="<?php echo esc_attr( $this->options->get( $id ) ); ?>"
                       />
            </p>
            <?php if ( $this->options->get( $id, 'description' ) ): ?>
                <p class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></p>
            <?php endif; ?>
            <?php
        }

        public function display_default_image( $args ) {
            $id      = $args[ 'id' ];
            $img_url = $this->get_img_url_by_option( $id );
            ?>
            <label for="upload_image">
                <p class="logo_preview" <?php
                if ( ! $img_url ): echo 'style="display:none"';
                endif;
                ?>>
                    <img src="<?php echo esc_url( $img_url ); ?>"
                         alt="<?php esc_html_e( 'Default Image', 'amphtml' ) ?>"
                         style="width: auto; height: 100px">
                </p>
                <input class="upload_image" type="hidden"
                       name="<?php echo $this->options->get( $id, 'name' ) ?>"
                       value="<?php echo esc_url( $img_url ); ?>"/>
                <input class="button upload_image_button" type="button"
                       value="<?php esc_html_e( 'Upload Image', 'amphtml' ); ?>"/>
                <input <?php
                if ( ! $this->options->get( $id ) ): echo 'style="display:none"';
                endif;
                ?>
                    class="button reset_image_button" type="button" value="<?php esc_html_e( 'Reset Image', 'amphtml' ); ?>"/>
                <p><?php esc_html_e( 'This image is required for Schema.org markup and will be used if you have not set up featured image for post/page. Image should be at least 1200 pixels wide.', 'amphtml' ) ?>
                    <a href="https://developers.google.com/search/docs/data-types/articles#article_types"
                       target="_blank"><?php esc_html_e( 'See requirements.', 'amphtml' ) ?></a></p>
            </label>
            <?php
        }

        public function get_countries() {
            return array(
                'AF' => 'Afghanistan',
                'AX' => '&#197;land Islands',
                'AL' => 'Albania',
                'DZ' => 'Algeria',
                'AS' => 'American Samoa',
                'AD' => 'Andorra',
                'AO' => 'Angola',
                'AI' => 'Anguilla',
                'AQ' => 'Antarctica',
                'AG' => 'Antigua and Barbuda',
                'AR' => 'Argentina',
                'AM' => 'Armenia',
                'AW' => 'Aruba',
                'AU' => 'Australia',
                'AT' => 'Austria',
                'AZ' => 'Azerbaijan',
                'BS' => 'Bahamas',
                'BH' => 'Bahrain',
                'BD' => 'Bangladesh',
                'BB' => 'Barbados',
                'BY' => 'Belarus',
                'BE' => 'Belgium',
                'PW' => 'Belau',
                'BZ' => 'Belize',
                'BJ' => 'Benin',
                'BM' => 'Bermuda',
                'BT' => 'Bhutan',
                'BO' => 'Bolivia',
                'BQ' => 'Bonaire, Saint Eustatius and Saba',
                'BA' => 'Bosnia and Herzegovina',
                'BW' => 'Botswana',
                'BV' => 'Bouvet Island',
                'BR' => 'Brazil',
                'IO' => 'British Indian Ocean Territory',
                'BN' => 'Brunei',
                'BG' => 'Bulgaria',
                'BF' => 'Burkina Faso',
                'BI' => 'Burundi',
                'KH' => 'Cambodia',
                'CM' => 'Cameroon',
                'CA' => 'Canada',
                'CV' => 'Cape Verde',
                'KY' => 'Cayman Islands',
                'CF' => 'Central African Republic',
                'TD' => 'Chad',
                'CL' => 'Chile',
                'CN' => 'China',
                'CX' => 'Christmas Island',
                'CC' => 'Cocos (Keeling) Islands',
                'CO' => 'Colombia',
                'KM' => 'Comoros',
                'CG' => 'Congo (Brazzaville)',
                'CD' => 'Congo (Kinshasa)',
                'CK' => 'Cook Islands',
                'CR' => 'Costa Rica',
                'HR' => 'Croatia',
                'CU' => 'Cuba',
                'CW' => 'Cura&ccedil;ao',
                'CY' => 'Cyprus',
                'CZ' => 'Czech Republic',
                'DK' => 'Denmark',
                'DJ' => 'Djibouti',
                'DM' => 'Dominica',
                'DO' => 'Dominican Republic',
                'EC' => 'Ecuador',
                'EG' => 'Egypt',
                'SV' => 'El Salvador',
                'GQ' => 'Equatorial Guinea',
                'ER' => 'Eritrea',
                'EE' => 'Estonia',
                'ET' => 'Ethiopia',
                'FK' => 'Falkland Islands',
                'FO' => 'Faroe Islands',
                'FJ' => 'Fiji',
                'FI' => 'Finland',
                'FR' => 'France',
                'GF' => 'French Guiana',
                'PF' => 'French Polynesia',
                'TF' => 'French Southern Territories',
                'GA' => 'Gabon',
                'GM' => 'Gambia',
                'GE' => 'Georgia',
                'DE' => 'Germany',
                'GH' => 'Ghana',
                'GI' => 'Gibraltar',
                'GR' => 'Greece',
                'GL' => 'Greenland',
                'GD' => 'Grenada',
                'GP' => 'Guadeloupe',
                'GU' => 'Guam',
                'GT' => 'Guatemala',
                'GG' => 'Guernsey',
                'GN' => 'Guinea',
                'GW' => 'Guinea-Bissau',
                'GY' => 'Guyana',
                'HT' => 'Haiti',
                'HM' => 'Heard Island and McDonald Islands',
                'HN' => 'Honduras',
                'HK' => 'Hong Kong',
                'HU' => 'Hungary',
                'IS' => 'Iceland',
                'IN' => 'India',
                'ID' => 'Indonesia',
                'IR' => 'Iran',
                'IQ' => 'Iraq',
                'IE' => 'Ireland',
                'IM' => 'Isle of Man',
                'IL' => 'Israel',
                'IT' => 'Italy',
                'CI' => 'Ivory Coast',
                'JM' => 'Jamaica',
                'JP' => 'Japan',
                'JE' => 'Jersey',
                'JO' => 'Jordan',
                'KZ' => 'Kazakhstan',
                'KE' => 'Kenya',
                'KI' => 'Kiribati',
                'KW' => 'Kuwait',
                'KG' => 'Kyrgyzstan',
                'LA' => 'Laos',
                'LV' => 'Latvia',
                'LB' => 'Lebanon',
                'LS' => 'Lesotho',
                'LR' => 'Liberia',
                'LY' => 'Libya',
                'LI' => 'Liechtenstein',
                'LT' => 'Lithuania',
                'LU' => 'Luxembourg',
                'MO' => 'Macao S.A.R., China',
                'MK' => 'North Macedonia',
                'MG' => 'Madagascar',
                'MW' => 'Malawi',
                'MY' => 'Malaysia',
                'MV' => 'Maldives',
                'ML' => 'Mali',
                'MT' => 'Malta',
                'MH' => 'Marshall Islands',
                'MQ' => 'Martinique',
                'MR' => 'Mauritania',
                'MU' => 'Mauritius',
                'YT' => 'Mayotte',
                'MX' => 'Mexico',
                'FM' => 'Micronesia',
                'MD' => 'Moldova',
                'MC' => 'Monaco',
                'MN' => 'Mongolia',
                'ME' => 'Montenegro',
                'MS' => 'Montserrat',
                'MA' => 'Morocco',
                'MZ' => 'Mozambique',
                'MM' => 'Myanmar',
                'NA' => 'Namibia',
                'NR' => 'Nauru',
                'NP' => 'Nepal',
                'NL' => 'Netherlands',
                'NC' => 'New Caledonia',
                'NZ' => 'New Zealand',
                'NI' => 'Nicaragua',
                'NE' => 'Niger',
                'NG' => 'Nigeria',
                'NU' => 'Niue',
                'NF' => 'Norfolk Island',
                'MP' => 'Northern Mariana Islands',
                'KP' => 'North Korea',
                'NO' => 'Norway',
                'OM' => 'Oman',
                'PK' => 'Pakistan',
                'PS' => 'Palestinian Territory',
                'PA' => 'Panama',
                'PG' => 'Papua New Guinea',
                'PY' => 'Paraguay',
                'PE' => 'Peru',
                'PH' => 'Philippines',
                'PN' => 'Pitcairn',
                'PL' => 'Poland',
                'PT' => 'Portugal',
                'PR' => 'Puerto Rico',
                'QA' => 'Qatar',
                'RE' => 'Reunion',
                'RO' => 'Romania',
                'RU' => 'Russia',
                'RW' => 'Rwanda',
                'BL' => 'Saint Barth&eacute;lemy',
                'SH' => 'Saint Helena',
                'KN' => 'Saint Kitts and Nevis',
                'LC' => 'Saint Lucia',
                'MF' => 'Saint Martin (French part)',
                'SX' => 'Saint Martin (Dutch part)',
                'PM' => 'Saint Pierre and Miquelon',
                'VC' => 'Saint Vincent and the Grenadines',
                'SM' => 'San Marino',
                'ST' => 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe',
                'SA' => 'Saudi Arabia',
                'SN' => 'Senegal',
                'RS' => 'Serbia',
                'SC' => 'Seychelles',
                'SL' => 'Sierra Leone',
                'SG' => 'Singapore',
                'SK' => 'Slovakia',
                'SI' => 'Slovenia',
                'SB' => 'Solomon Islands',
                'SO' => 'Somalia',
                'ZA' => 'South Africa',
                'GS' => 'South Georgia/Sandwich Islands',
                'KR' => 'South Korea',
                'SS' => 'South Sudan',
                'ES' => 'Spain',
                'LK' => 'Sri Lanka',
                'SD' => 'Sudan',
                'SR' => 'Suriname',
                'SJ' => 'Svalbard and Jan Mayen',
                'SZ' => 'Swaziland',
                'SE' => 'Sweden',
                'CH' => 'Switzerland',
                'SY' => 'Syria',
                'TW' => 'Taiwan',
                'TJ' => 'Tajikistan',
                'TZ' => 'Tanzania',
                'TH' => 'Thailand',
                'TL' => 'Timor-Leste',
                'TG' => 'Togo',
                'TK' => 'Tokelau',
                'TO' => 'Tonga',
                'TT' => 'Trinidad and Tobago',
                'TN' => 'Tunisia',
                'TR' => 'Turkey',
                'TM' => 'Turkmenistan',
                'TC' => 'Turks and Caicos Islands',
                'TV' => 'Tuvalu',
                'UG' => 'Uganda',
                'UA' => 'Ukraine',
                'AE' => 'United Arab Emirates',
                'GB' => 'United Kingdom (UK)',
                'US' => 'United States (US)',
                'UM' => 'United States (US) Minor Outlying Islands',
                'UY' => 'Uruguay',
                'UZ' => 'Uzbekistan',
                'VU' => 'Vanuatu',
                'VA' => 'Vatican',
                'VE' => 'Venezuela',
                'VN' => 'Vietnam',
                'VG' => 'Virgin Islands (British)',
                'VI' => 'Virgin Islands (US)',
                'WF' => 'Wallis and Futuna',
                'EH' => 'Western Sahara',
                'WS' => 'Samoa',
                'YE' => 'Yemen',
                'ZM' => 'Zambia',
                'ZW' => 'Zimbabwe',
            );
        }

    }

}