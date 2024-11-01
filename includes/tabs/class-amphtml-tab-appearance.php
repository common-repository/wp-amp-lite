<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if ( ! class_exists( 'AMPHTML_Tab_Appearance' ) ) {

    class AMPHTML_Tab_Appearance extends AMPHTML_Tab_Abstract {

        public function __construct( $name, $options, $is_current = false ) {
            parent::__construct( $name, $options, $is_current );
            add_action( 'amphtml_proceed_settings_form', array( $this, 'remove_outdated_min_css' ) );
        }

        public function get_fields() {
            return array_merge( $this->get_color_fields( 'colors' ), $this->get_font_fields( 'fonts' ), $this->get_header_fields( 'header' ), $this->get_footer_fields( 'footer' ), $this->get_post_meta_data_fields( 'post_meta_data' ), $this->get_social_share_buttons_fields( 'social_share_buttons' ), $this->get_social_buttons_fields( 'social_buttons' ));
        }

        public function get_sections() {
            return array(
                'colors'               => esc_html__( 'Colors', 'amphtml' ),
                'fonts'                => esc_html__( 'Fonts', 'amphtml' ),
                'header'               => esc_html__( 'Header', 'amphtml' ),
                'footer'               => esc_html__( 'Footer', 'amphtml' ),
                'post_meta_data'       => esc_html__( 'Post Meta Data', 'amphtml' ),
                'social_share_buttons' => esc_html__( 'Social Share Buttons', 'amphtml' ),
                'social_buttons'       => esc_html__( 'Social Buttons', 'amphtml' ),                
            );
        }

        public function get_font_fields( $section ) {
            return array(
                array(
                    'id'                    => 'logo_font',
                    'title'                 => esc_html__( 'Logo', 'amphtml' ),
                    'default'               => 'sans-serif',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_font_select' ),
                    'display_callback_args' => array( 'id' => 'logo_font' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'menu_font',
                    'title'                 => esc_html__( 'Menu', 'amphtml' ),
                    'default'               => 'sans-serif',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_font_select' ),
                    'display_callback_args' => array( 'id' => 'menu_font' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'title_font',
                    'title'                 => esc_html__( 'Title', 'amphtml' ),
                    'default'               => 'sans-serif',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_font_select' ),
                    'display_callback_args' => array( 'id' => 'title_font' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'post_meta_font',
                    'title'                 => esc_html__( 'Post Meta', 'amphtml' ),
                    'default'               => 'sans-serif',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_font_select' ),
                    'display_callback_args' => array( 'id' => 'post_meta_font' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'content_font',
                    'title'                 => esc_html__( 'Content', 'amphtml' ),
                    'default'               => 'sans-serif',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_font_select' ),
                    'display_callback_args' => array( 'id' => 'content_font' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'footer_font',
                    'title'                 => esc_html__( 'Footer', 'amphtml' ),
                    'default'               => 'sans-serif',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_font_select' ),
                    'display_callback_args' => array( 'id' => 'footer_font' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'custom_fonts',
                    'title'                 => esc_html__( 'Custom Fonts', 'amphtml' ),
                    'default'               => '',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_custom_fonts' ),
                    'display_callback_args' => array( 'id' => 'custom_fonts' ),
                    'sanitize_callback'     => array( $this, 'sanitize_custom_fonts' ),
                    'description'           => '',
                ),
            );
        }

        public function get_color_fields( $section ) {
            $fields = array(
                array(
                    'id'                    => 'background_color',
                    'title'                 => esc_html__( 'Page Background', 'amphtml' ),
                    'default'               => '#FFFFFF',
                    'display_callback_args' => array( 'id' => 'background_color' ),
                ),
                array(
                    'id'                    => 'header_color',
                    'title'                 => esc_html__( 'Header Background', 'amphtml' ),
                    'default'               => '#ffffff',
                    'display_callback_args' => array( 'id' => 'header_color' ),
                ),
                array(
                    'id'                    => 'header_text_color',
                    'title'                 => esc_html__( 'Header Text', 'amphtml' ),
                    'default'               => '#8d447b',
                    'display_callback_args' => array( 'id' => 'header_text_color' ),
                ),
                array(
                    'id'                    => 'header_menu_color',
                    'title'                 => esc_html__( 'Header Menu Background', 'amphtml' ),
                    'default'               => '#8d447b',
                    'display_callback_args' => array( 'id' => 'header_menu_color' ),
                ),
                array(
                    'id'                    => 'header_menu_text_color',
                    'title'                 => esc_html__( 'Header Menu Text', 'amphtml' ),
                    'default'               => '#ffffff',
                    'display_callback_args' => array( 'id' => 'header_menu_text_color' ),
                ),
                array(
                    'id'                    => 'main_title_color',
                    'title'                 => esc_html__( 'Main Title', 'amphtml' ),
                    'default'               => '#88457b',
                    'display_callback_args' => array( 'id' => 'main_title_color' ),
                ),
                array(
                    'id'                    => 'main_text_color',
                    'title'                 => esc_html__( 'Main Text', 'amphtml' ),
                    'default'               => '#3d596d',
                    'display_callback_args' => array( 'id' => 'main_text_color' ),
                ),
                array(
                    'id'                    => 'link_color',
                    'title'                 => esc_html__( 'Link Text', 'amphtml' ),
                    'default'               => '#88457b',
                    'display_callback_args' => array( 'id' => 'link_color' ),
                ),
                array(
                    'id'                    => 'link_hover',
                    'title'                 => esc_html__( 'Link Hover', 'amphtml' ),
                    'default'               => '#2e4453',
                    'display_callback_args' => array( 'id' => 'link_hover' ),
                ),
                array(
                    'id'                    => 'inputs_color',
                    'title'                 => esc_html__( 'Inputs Color', 'amphtml' ),
                    'default'               => '#88457b',
                    'display_callback_args' => array( 'id' => 'inputs_color' ),
                ),
                array(
                    'id'                    => 'footer_text_color',
                    'title'                 => esc_html__( 'Footer Text', 'amphtml' ),
                    'default'               => '#FFFFFF',
                    'display_callback_args' => array( 'id' => 'footer_text_color' ),
                ),
                array(
                    'id'                    => 'footer_color',
                    'title'                 => esc_html__( 'Footer Background', 'amphtml' ),
                    'default'               => '#252525',
                    'display_callback_args' => array( 'id' => 'footer_color' ),
                ),
            );

            // set common options
            foreach ( $fields as &$field ) {
                $field[ 'display_callback' ]  = array( $this, 'display_color_field' );
                $field[ 'sanitize_callback' ] = array( $this, 'sanitize_color' );
                $field[ 'section' ]           = $section;
            }

            $fields = apply_filters( 'amphtml_color_fields', $fields, $this, $section );

            return $fields;
        }

        public function get_header_fields( $section ) {
            return array(
                array(
                    'id'                    => 'favicon',
                    'title'                 => esc_html__( 'Favicon', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_favicon' ),
                    'display_callback_args' => array( 'id' => 'favicon' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'header_menu',
                    'title'                 => esc_html__( 'Header Menu', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'header_menu' ),
                    'description'           => esc_html__( 'Show header menu', 'amphtml' ) . '(<a href="' . add_query_arg( array( 'action' => 'locations' ), admin_url( 'nav-menus.php' ) ) . '" target="_blank">' . esc_html__( 'set AMP menu', 'amphtml' ) . '</a>)',
                ),
                array(
                    'id'                    => 'header_menu_type',
                    'title'                 => esc_html__( 'Header Menu Type', 'amphtml' ),
                    'default'               => 'accordion',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_header_menu_type' ),
                    'display_callback_args' => array(
                        'id'             => 'header_menu_type',
                        'select_options' => array(
                            'accordion' => 'Accordion',
                            'sidebar'   => 'Sidebar',
                        ) ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'header_menu_button',
                    'title'                 => esc_html__( 'Header Menu Button', 'amphtml' ),
                    'default'               => 'text',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_header_menu_button' ),
                    'display_callback_args' => array(
                        'id'             => 'header_menu_button',
                        'select_options' => array(
                            'text' => 'Text',
                            'icon' => 'Icon',
                        ) ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'logo_opt',
                    'title'                 => esc_html__( 'Logo Type', 'amphtml' ),
                    'default'               => 'text_logo',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_logo_opt' ),
                    'display_callback_args' => array( 'id' => 'logo_opt' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'logo_text',
                    'title'                 => esc_html__( 'Logo Text', 'amphtml' ),
                    'default'               => get_bloginfo( 'name' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'logo_text' ),
                    'description'           => '',
                ),
                array(
                    'id'                    => 'logo',
                    'title'                 => esc_html__( 'Logo Icon', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_logo' ),
                    'display_callback_args' => array( 'id' => 'logo' ),
                    'description'           => '',
                ),
            );
        }

        public function get_footer_fields( $section ) {
            return array(
                array(
                    'id'                    => 'footer_menu',
                    'title'                 => esc_html__( 'Footer Menu', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'footer_menu' ),
                    'description'           => esc_html__( 'Show footer menu', 'amphtml' ) . '(<a href="' . add_query_arg( array( 'action' => 'locations' ), admin_url( 'nav-menus.php' ) ) . '" target="_blank">' . esc_html__( 'set footer AMP menu ', 'amphtml' ) . '</a>)',
                ),
                array(
                    'id'                    => 'footer_content',
                    'title'                 => esc_html__( 'Footer Content', 'amphtml' ),
                    'default'               => '',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_footer_content' ),
                    'display_callback_args' => array( 'id' => 'footer_content' ),
                    'sanitize_callback'     => array( $this, 'sanitize_footer_content' ),
                    'description'           => esc_html__( 'This is the footer content block for all AMP pages. <br>' . 'Leave empty to hide footer at all. <br>' . 'Plain html without inline styles allowed. ' . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' ),
                ),
                array(
                    'id'                    => 'footer_scrolltop',
                    'title'                 => esc_html__( 'Scroll to Top Button', 'amphtml' ),
                    'default'               => esc_html__( 'Back to top', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'footer_scrolltop' ),
                    'description'           => esc_html__( 'Leave empty to hide this button.', 'amphtml' ),
                ),
                array(
                    'id'                    => 'footer_social',
                    'title'                 => esc_html__( 'Social Buttons', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'footer_social' ),
                    'description'           => esc_html__( 'Show social button.', 'amphtml' ),
                ),
            );
        }

        public function get_post_meta_data_fields( $section ) {
            return array(
                array(
                    'id'                    => 'post_meta_author',
                    'title'                 => esc_html__( 'Author', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'post_meta_author' ),
                    'description'           => esc_html__( 'Show post author', 'amphtml' ),
                ),
                array(
                    'id'                    => 'post_meta_categories',
                    'title'                 => esc_html__( 'Categories', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'post_meta_categories' ),
                    'description'           => esc_html__( 'Show post categories', 'amphtml' ),
                ),
                array(
                    'id'                    => 'post_meta_tags',
                    'title'                 => esc_html__( 'Tags', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'post_meta_tags' ),
                    'description'           => esc_html__( 'Show post tags', 'amphtml' ),
                ),
                array(
                    'id'                    => 'post_meta_date_format',
                    'title'                 => esc_html__( 'Date Format', 'amphtml' ),
                    'default'               => 'default',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_date_format' ),
                    'display_callback_args' => array( 'id' => 'post_meta_date_format' ),
                    'description'           => '(<a href="https://codex.wordpress.org/Formatting_Date_and_Time#Examples">Examples of Date Format</a>)',
                ),
            );
        }

        public function get_social_share_buttons_fields( $section ) {
            return array(
                array(
                    'id'                    => 'social_share_buttons',
                    'title'                 => esc_html__( 'Social Share Buttons', 'amphtml' ),
                    'default'               => array( 'facebook', 'twitter', 'linkedin' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_multiple_select' ),
                    'display_callback_args' => array(
                        'id'             => 'social_share_buttons',
                        'select_options' => array(
                            'facebook'  => esc_html__( 'Facebook', 'amphtml' ),
                            'twitter'   => esc_html__( 'Twitter', 'amphtml' ),
                            'linkedin'  => esc_html__( 'LinkedIn', 'amphtml' ),
                            'pinterest' => esc_html__( 'Pinterest', 'amphtml' ),
                            'whatsapp'  => esc_html__( 'WhatsApp', 'amphtml' ),
                            'email'     => esc_html__( 'Email', 'amphtml' ),
                        )
                    ),
                ),
                array(
                    'id'                    => 'social_like_button',
                    'title'                 => esc_html__( 'Facebook Like', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'social_like_button' ),
                    'description'           => esc_html__( 'Show Facebook like button', 'amphtml' ),
                ),
            );
        }

        public function get_social_buttons_fields( $section ) {
            return array(
                array(
                    'id'                    => 'social_instagram',
                    'title'                 => esc_html__( 'Instagram', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'social_instagram' ),
                    'description'           => esc_html__( 'Instagram', 'amphtml' ),
                ),
                array(
                    'id'                    => 'social_facebook',
                    'title'                 => esc_html__( 'Facebook', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'social_facebook' ),
                    'description'           => esc_html__( 'Facebook', 'amphtml' ),
                ),
                array(
                    'id'                    => 'social_twitter',
                    'title'                 => esc_html__( 'Twitter', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'social_twitter' ),
                    'description'           => esc_html__( 'Twitter', 'amphtml' ),
                ),
                array(
                    'id'                    => 'social_linkedin',
                    'title'                 => esc_html__( 'LinkedIn', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'social_linkedin' ),
                    'description'           => esc_html__( 'LinkedIn', 'amphtml' ),
                ),
                array(
                    'id'                    => 'social_pinterest',
                    'title'                 => esc_html__( 'Pinterest', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'social_pinterest' ),
                    'description'           => esc_html__( 'Pinterest', 'amphtml' ),
                ),
                array(
                    'id'                    => 'social_youtube',
                    'title'                 => esc_html__( 'YouTube', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'social_youtube' ),
                    'description'           => esc_html__( 'YouTube', 'amphtml' ),
                ),
            );
        }

        /*
         * Color Section
         */

        public function sanitize_color( $color, $id = 'empty' ) {
            // Validate Color
            $background = trim( $color );
            $background = strip_tags( stripslashes( $background ) );

            // Check if is a valid hex color
            if ( false === $this->options->check_header_color( $background ) ) {
                add_settings_error( $this->options->get( $id, 'name' ), 'hc_error', esc_html__( 'Insert a valid color', 'amphtml' ), 'error' );
                $valid_field = $this->options->get( $id );
            } else {
                $valid_field = $background;
            }

            return $valid_field;
        }

        /*
         *  Font Section
         */

        public function get_fonts_list() {
            $fonts = array(
                'sans-serif',
                'Work Sans',
                'Alegreya',
                'Fira Sans',
                'Lora',
                'Merriweather',
                'Montserrat',
                'Open Sans',
                'Playfair Display',
                'Roboto',
                'Lato',
                'Cardo',
                'Arvo',
            );
            $fonts = array_merge( $fonts, $this->get_custom_font_name() );

            return $fonts;
        }

        public function get_custom_font_name() {

            $custom_font_name = array();

            $custom_fonts = $this->options->get( 'custom_fonts' );
            if ( ! empty( $custom_fonts ) ) {
                foreach ( $custom_fonts as $custom_font ) {
                    if ( ! empty( $custom_font[ 'name' ] ) && ( ! empty( $custom_font[ 'link' ] ) || ! empty( $custom_font[ 'link_bold' ] ) ) ) {
                        $custom_font_name[] = $custom_font[ 'name' ];
                    }
                }
            }
            return $custom_font_name;
        }

        public function display_font_select( $args ) {
            $id = $args[ 'id' ];
            ?>
            <label for="<?php echo $id ?>">
                <select style="width: 28%" id="<?php echo $id ?>" name="<?php echo $this->options->get( $id, 'name' ) ?>">
                    <?php foreach ( $this->get_fonts_list() as $title ): ?>
                        <?php $name = str_replace( ' ', '+', $title ) ?>
                        <option value="<?php echo $name ?>" <?php selected( $this->options->get( $id ), $name ) ?>>
                            <?php printf( esc_html__( '%s', 'amphtml' ), $title ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php
        }

        public function display_custom_fonts( $args ) {
            $id           = $args[ 'id' ];
            $custom_fonts = $this->options->get( $id );
            ?>
            <script type="text/x-template" id="amphtml-custom-font-tmpl">
                <fieldset id="custom-font-__N__" class="amphtml-custom-font" data-font="<?php esc_html_e( 'Font ', 'amphtml' ); ?> __N__">
                <legend><?php esc_html_e( 'Font ', 'amphtml' ); ?> __N__</legend>
                <p>
                <input  style="width: 28%" type="text" name="<?php echo $this->options->get( $id, 'name' ) ?>[__N__][name]"
                value="" placeholder="<?php esc_html_e( 'Font name ', 'amphtml' ); ?>" />
                </p>
                <p class="description"><?php esc_html_e( 'Font name', 'amphtml' ); ?></p>
                <br>
                <p>
                <input style="width: 28%" type="text" name="<?php echo $this->options->get( $id, 'name' ) ?>[__N__][link]"
                value="" placeholder="<?php esc_html_e( 'Font link', 'amphtml' ); ?>" />
                </p>
                <p class="description"><?php esc_html_e( 'Path to the font file (font-weight: regular)', 'amphtml' ); ?></p>
                <br>
                <p>
                <input style="width: 28%" type="text" name="<?php echo $this->options->get( $id, 'name' ) ?>[__N__][link_bold]"
                value="" placeholder="<?php esc_html_e( 'Bold Font link', 'amphtml' ); ?>" />
                </p>
                <p class="description"><?php esc_html_e( 'Path to the font file (font-weight: bold)', 'amphtml' ); ?></p>
                <br>
                <button class="button-link button-link-delete amphtml-delete-font" type="button"
                aria-label="<?php esc_html_e( 'Delete font', 'amphtml' ); ?>"><?php esc_html_e( 'Delete the font', 'amphtml' ); ?></button>
                </fieldset>
            </script>
            <?php
            if ( ! empty( $custom_fonts ) ) {
                $i = 0;
                foreach ( $custom_fonts as $custom_font ) {
                    if ( ! empty( $custom_font[ 'name' ] ) || ! empty( $custom_font[ 'link' ] ) ) {
                        $i ++;
                        ?>
                        <fieldset id="custom-font-<?php echo $i; ?>"
                                  class="amphtml-custom-font"
                                  data-font="<?php esc_html_e( 'Font ', 'amphtml' ); ?> __N__">
                            <legend><?php esc_html_e( 'Font ', 'amphtml' ); ?><?php echo $i; ?></legend>
                            <p>
                                <input  style="width: 28%" type="text"
                                        name="<?php echo $this->options->get( $id, 'name' ) ?>[<?php echo $i; ?>][name]"
                                        value="<?php echo esc_attr( $custom_font[ 'name' ] ); ?>"
                                        placeholder="<?php esc_html_e( 'Font name', 'amphtml' ); ?>" />
                            </p>
                            <p class="description"><?php esc_html_e( 'Font name', 'amphtml' ); ?></p>
                            <br>
                            <p>
                                <input style="width: 28%" type="text"
                                       name="<?php echo $this->options->get( $id, 'name' ) ?>[<?php echo $i; ?>][link]"
                                       value="<?php echo esc_attr( $custom_font[ 'link' ] ); ?>"
                                       placeholder="<?php esc_html_e( 'Font link', 'amphtml' ); ?>" />
                            </p>
                            <p class="description"><?php esc_html_e( 'Path to the font file (font-weight: regular)', 'amphtml' ); ?></p>
                            <br>
                            <p>
                                <input style="width: 28%" type="text"
                                       name="<?php echo $this->options->get( $id, 'name' ) ?>[<?php echo $i; ?>][link_bold]"
                                       value="<?php echo esc_attr( $custom_font[ 'link_bold' ] ); ?>"
                                       placeholder="<?php esc_html_e( 'Bold Font link', 'amphtml' ); ?>" />
                            </p>
                            <p class="description"><?php esc_html_e( 'Path to the font file (font-weight: bold)', 'amphtml' ); ?></p>
                            <br>
                            <button class="button-link button-link-delete amphtml-delete-font"
                                    type="button"
                                    aria-label="<?php esc_html_e( 'Delete the font', 'amphtml' ); ?>"><?php esc_html_e( 'Delete the font', 'amphtml' ); ?></button>
                        </fieldset>
                        <?php
                    }
                }
            }
            ?>
            <button id="amphtml-add-font" class="button button-secondary" type="button"><?php esc_html_e( 'Add font', 'amphtml' ); ?></button>
            <?php
        }

        /*
         *  Header Section
         */

        public function display_header_menu_type( $args ) {
            $this->display_select( $args );
        }

        public function display_header_menu_button( $args ) {
            $this->display_select( $args );
        }

        public function display_logo_opt( $args ) {
            $id = $args[ 'id' ];
            ?>
            <select style="width: 28%" id="<?php echo $id; ?>" name="<?php echo $this->options->get( $id, 'name' ) ?>">
                <option value="icon_logo" <?php selected( $this->options->get( $id ), 'icon_logo' ) ?>>
                    <?php esc_html_e( 'Icon Logo', 'amphtml' ); ?>
                </option>
                <option value="text_logo" <?php selected( $this->options->get( $id ), 'text_logo' ) ?>>
                    <?php esc_html_e( 'Text Logo', 'amphtml' ); ?>
                </option>
                <option value="icon_an_text" <?php selected( $this->options->get( $id ), 'icon_an_text' ) ?>>
                    <?php esc_html_e( 'Icon and Text Logo', 'amphtml' ); ?>
                </option>
                <option value="image_logo" <?php selected( $this->options->get( $id ), 'image_logo' ) ?>>
                    <?php esc_html_e( 'Image Logo', 'amphtml' ); ?>
                </option>
            </select>
            <?php
        }

        public function display_logo( $args ) {
            $id       = $args[ 'id' ];
            $logo_url = $this->get_img_url_by_option( $id );
            ?>
            <label for="upload_image">
                <p class="logo_preview hide_preview" <?php
                if ( ! $logo_url ): echo 'style="display:none"';
                endif;
                ?>>
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_html_e( 'Site Logo', 'amphtml' ) ?>"
                         style="width: auto; height: 100px">
                </p>
                <input class="upload_image" type="hidden" name="<?php echo $this->options->get( 'logo', 'name' ) ?>"
                       value="<?php echo esc_url( $logo_url ); ?>"/>
                <input class="upload_image_button button" type="button" value="<?php esc_html_e( 'Upload Image', 'amphtml' ) ?>"/>
                <input <?php
                if ( ! $logo_url ): echo 'style="display:none"';
                endif;
                ?>
                    class="reset_image_button button" type="button" value="<?php esc_html_e( 'Reset Image', 'amphtml' ) ?>"/>
                <p class="img_text_size_full"
                   style="display:none"><?php esc_html_e( 'The image will have full size.', 'amphtml' ) ?></p>
                <p class="img_text_size img_text_size_logo"><?php esc_html_e( 'The image will be resized to fit in a 32x32 box (but not cropped)', 'amphtml' ) ?></p>
            </label>
            <?php
        }

        public function display_favicon( $args ) {
            $id       = $args[ 'id' ];
            $logo_url = $this->get_img_url_by_option( $id );
            ?>
            <label for="upload_image">
                <p class="logo_preview" <?php
                if ( ! $logo_url ): echo 'style="display:none"';
                endif;
                ?>>
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_html_e( 'Site Favicon', 'amphtml' ) ?>"
                         style="width: auto; height: 100px">
                </p>
                <input class="upload_image" type="hidden" name="<?php echo $this->options->get( 'favicon', 'name' ) ?>"
                       value="<?php echo esc_url( $logo_url ); ?>"/>
                <input class="upload_image_button button" type="button" value="<?php esc_html_e( 'Upload Image', 'amphtml' ) ?>"/>
                <input <?php
                if ( ! $logo_url ): echo 'style="display:none"';
                endif;
                ?>
                    class="reset_image_button button" type="button" value="<?php esc_html_e( 'Reset Image', 'amphtml' ) ?>"/>
                <p class="img_text_size_full_favicon"><?php esc_html_e( 'The image will have full size.', 'amphtml' ) ?></p>
            </label>
            <?php
        }

        /*
         * Footer Section
         */

        public function sanitize_footer_content( $footer_content ) {
            $tags                           = wp_kses_allowed_html( 'post' );
            $tags[ 'form' ][ 'action-xhr' ] = true;

            $not_allowed = array(
                'font',
                'form',
                'menu',
                'nav'
            );

            foreach ( $tags as $key => $attr ) {
                if ( in_array( $key, $not_allowed ) ) {
                    unset( $tags[ $key ] );
                }
            }

            $tags = apply_filters( 'wpamp_content_tags', $tags );

            return wp_kses( $footer_content, $tags );
        }

        public function display_footer_content( $args ) {
            $id = $args[ 'id' ];
            ?>
            <textarea name="<?php echo $this->options->get( $id, 'name' ) ?>" rows="6"
                      cols="60"><?php echo trim( $this->options->get( $id ) ); ?></textarea>
                      <?php if ( $this->options->get( $id, 'description' ) ): ?>
                <p class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></p>
                <?php
            endif;
        }

        public function display_date_format( $args ) {
            $id     = $args[ 'id' ];
            ?>
            <fieldset>
                <?php
                $custom = true;

                echo "\t<label><input type='radio' name='" . $this->options->get( $id, 'name' ) . "' value='none'";
                if ( 'none' === $this->options->get( $id ) ) {
                    echo " checked='checked'";
                    $custom = false;
                }
                echo ' /></span> ' . esc_html__( 'None', 'amphtml' ) . "</label><br />\n";


                echo "\t<label><input type='radio' name='" . $this->options->get( $id, 'name' ) . "' value='relative'";
                if ( 'relative' === $this->options->get( $id ) ) {
                    echo " checked='checked'";
                    $custom = false;
                }

                echo ' /> <span class="date-time-text format-i18n">' . esc_html( sprintf( _x( '%s ago', '%s = human-readable time difference', 'amphtml' ), human_time_diff( get_the_date() ) ) ) . '</span> (' . esc_html__( 'Relative', 'amphtml' ) . ")</label><br />\n";


                echo "\t<label><input type='radio' name='" . $this->options->get( $id, 'name' ) . "' value='default'";
                if ( 'default' === $this->options->get( $id ) ) {
                    echo " checked='checked'";
                    $custom = false;
                }
                echo ' /> <span class="date-time-text format-i18n">' . date_i18n( get_option( 'date_format' ) ) . '</span> (' . esc_html__( 'Default system format', 'amphtml' ) . ")</label><br />\n";

                $custom_value = strlen( get_option( 'amphtml_post_meta_date_format_custom' ) ) ? get_option( 'amphtml_post_meta_date_format_custom' ) : esc_html__( 'F j, Y', 'amphtml' );

                echo '<label><input type="radio" name="' . $this->options->get( $id, 'name' ) . '" id="date_format_custom_radio" value="custom"';
                checked( $custom );
                echo '/> <span class="date-time-text date-time-custom-text">' . esc_html__( 'Custom:', 'amphtml' ) . '</label>' . '<input type="text" name="amphtml_post_meta_date_format_custom" id="amphtml_date_format_custom" value="' . esc_attr( $custom_value ) . '" style="width:60px" /></span>' . '<span class="example">' . date_i18n( $custom_value ) . '</span>' . "<span class='spinner'></span>\n";
                ?>
                <span
                    class="description"><?php esc_html_e( $this->options->get( $id, 'description' ), 'amphtml' ) ?></span>
            </fieldset>
            <?php
        }

        public function get_submit() { //todo replace with action
            ?>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary"
                       value="<?php esc_html_e( 'Save Changes', 'amphtml' ); ?>">
                       <?php if ( 'colors' == $this->get_current_section() ): ?>
                    <input type="submit" name="reset" id="reset" class="button"
                           value="<?php esc_html_e( 'Default theme settings', 'amphtml' ); ?>" style="margin-left: 10px;">
                       <?php endif; ?>
            </p>
            <?php
        }

        public function display_textarea_field( $args ) {
            $id = $args[ 'id' ];
            ?>
            <textarea name="<?php echo $this->options->get( $id, 'name' ) ?>" id="amp_css_entry"
                      style="width:100%;height:300px;"
                      <?php echo ( $this->options->get( $id, 'placeholder' ) ) ? 'placeholder="' . $this->options->get( $id, 'placeholder' ) . '"' : '' ?>><?php echo esc_attr( $this->options->get( 'extra_css_amp' ) ); ?></textarea>
            <p class="description">
                <strong><?php esc_html_e( 'Important', 'amphtml' ); ?>: </strong><span><?php esc_html_e( 'Do not use disallowed styles for avoiding AMP validation errors.', 'amphtml' ); ?>
                    <?php esc_html_e( 'Please see', 'amphtml' ); ?>: <a
                        href="https://www.ampproject.org/docs/guides/responsive/style_pages" target="_blank">
                        <?php esc_html_e( 'Supported CSS', 'amphtml' ); ?></a>.</span>
            </p>
            <?php
        }

        public function remove_outdated_min_css( $options ) {
            if ( isset( $_REQUEST[ 'settings-updated' ] ) && 'true' == $_REQUEST[ 'settings-updated' ] && $this->is_current() ) {
                $styles   = array( 'style', 'rtl-style' );
                $template = new AMPHTML_Template( $options );
                foreach ( $styles as $filename ) {
                    if ( $path = $template->get_minify_style_path( $filename ) ) {
                        unlink( $path );
                    }
                    $template->generate_minified_css_file( $filename );
                }
            }
        }

        public function get_section_callback( $id ) {
            return array( $this, 'section_callback' );
        }

        public function section_callback( $page, $section ) {
            global $wp_settings_fields;

            $custom_fields = array(
                'logo_text',
            );

            if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
                return;
            }
            echo '<table class="form-table">';
            $row_id = 0;
            foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
                $class = '';

                if ( ! method_exists( $field[ 'callback' ][ 0 ], $field[ 'callback' ][ 1 ] ) ) {
                    continue;
                }

                if ( ! empty( $field[ 'args' ][ 'class' ] ) ) {
                    $class = ' class="' . esc_attr( $field[ 'args' ][ 'class' ] ) . '"';
                }

                if ( in_array( $field[ 'id' ], $custom_fields ) ) {
                    echo "<tr{$class} style='display: none'>";
                } else {
                    echo "<tr data-name='{$field[ 'id' ]}' id='pos_{$row_id}' {$class}>";
                }

                if ( ! empty( $field[ 'args' ][ 'label_for' ] ) ) {
                    echo '<th scope="row"><label for="' . esc_attr( $field[ 'args' ][ 'label_for' ] ) . '">' . $field[ 'title' ] . '</label></th>';
                } else {
                    echo '<th scope="row">' . $field[ 'title' ] . '</th>';
                }

                echo '<td>';
                call_user_func( $field[ 'callback' ], $field[ 'args' ] );
                echo '</td>';
                echo '</tr>';
                $row_id ++;
            }

            echo '</table>';
        }

    }

}