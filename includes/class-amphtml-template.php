<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

require_once 'class-amphtml-template-abstract.php';
require_once 'class-amphtml-sanitize.php';
if ( ! class_exists( 'AMPHTML_Template' ) ) {

    class AMPHTML_Template extends AMPHTML_Template_Abstract {

        public function __construct( $options ) {

            if ( true === AMPHTML()->is_amp() ) {
                remove_shortcode( 'gallery' );
                add_filter( 'comments_template', array( $this, 'get_comments_template_path' ) );
                add_filter( 'term_link', array( $this, 'update_meta_links' ) );
                add_filter( 'wp_setup_nav_menu_item', array( $this, 'update_menu_item_url' ) );
                add_filter( 'get_pagenum_link', array( $this, 'update_pagination_link' ) );
            }

            $this->properties   = array();
            $this->options      = $options;
            $this->sanitizer    = new AMPHTML_Sanitize( $this );
            $this->doc_title    = function_exists( 'wp_get_document_title' ) ? wp_get_document_title() : wp_title( '', false );
            $this->base_url     = home_url() . '/';
            $this->blog_name    = $this->options->get( 'logo_text' );
            $this->logo         = $this->options->get( 'logo' );
            $this->default_logo = $this->options->get( 'default_logo' );
            $this->favicon      = $this->get_favicon();

            $this->old_styles_dir_name = 'deprec-style';

            add_action( 'amphtml_template_head', array( $this, 'page_fonts' ) );
            add_action( 'amphtml_template_css', array( $this, 'get_custom_css' ) );

            if ( $this->options->get( 'header_menu' ) ) {
                $menu_handler = $this->get_menu_handler( $this->options->get( 'header_menu_type' ) );
                $this->add_embedded_element( $menu_handler );
            }
            add_action( 'amphtml_before_header', array( $this, 'remove_term_link_filter' ) );
        }

        protected function get_favicon() {
            $icon    = $this->options->get( 'favicon' );
            if ( $img_obj = json_decode( $icon ) ) {
                return $img_obj->url;
            }

            return $icon;
        }

        public function get_language_attributes() {
            if ( $lang = get_bloginfo( 'language' ) ) {
                $lang_atts = "lang=\"$lang\"";

                return apply_filters( 'amphtml_language_attributes', $lang_atts );
            }

            return '';
        }

        /**
         * Multi page content render - <!--nextpage-->
         *
         * @global type $page
         *
         * @param object $content
         *
         * @return string content
         */
        public function multipage_content( $content ) {
            global $page;

            $page    = $page ? $page : 1;
            $content = $content->save();

            if ( false !== strpos( $content, '<!--nextpage-->' ) ) {
                $content = str_replace( "\n<!--nextpage-->\n", '<!--nextpage-->', $content );
                $content = str_replace( "\n<!--nextpage-->", '<!--nextpage-->', $content );
                $content = str_replace( "<!--nextpage-->\n", '<!--nextpage-->', $content );

                // Ignore nextpage at the beginning of the content.
                if ( 0 === strpos( $content, '<!--nextpage-->' ) ) {
                    $content = substr( $content, 15 );
                }

                $pages   = explode( '<!--nextpage-->', $content );
                $content = $pages[ $page - 1 ];
                add_filter( 'wp_link_pages_link', array( $this, 'wpamp_link_pages' ) );

                return $content;
            }

            return $content;
        }

        /**
         * Make page pagination for amp
         *
         * @param string $link
         *
         * @return string amp-link
         */
        public function wpamp_link_pages( $link ) {
            // The Regular Expression filter
            $reg_exUrl = "#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#";
            // Check if there is a url
            if ( preg_match( $reg_exUrl, $link, $url ) ) {
                $amp_link = str_replace( $url[ 0 ], $this->get_amphtml_link( $url[ 0 ] ), $link );

                return $amp_link;
            }

            return $link;
        }

        public function page_fonts() {
            $used_fonts       = array();
            $custom_font_name = $this->options->get_tabs()->get( 'appearance' )->get_custom_font_name();

            foreach ( $this->options->get_tabs()->get( 'appearance' )->get_font_fields( 'fonts' ) as $font ) {
                $font_name = $this->options->get( $font[ 'id' ] );
                if ( ! is_array( $font_name ) && $font_name != $font[ 'default' ] && ! in_array( $font_name, $used_fonts ) && ! in_array( $font_name, $custom_font_name ) ) {
                    $additional_styles = apply_filters( 'amphtml_font_styles', ':400,700,400italic,500,500italic' );
                    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $font_name . $additional_styles . '">' . PHP_EOL;
                }
                $used_fonts[]                 = $font_name;
                $this->fonts[ $font[ 'id' ] ] = str_replace( '+', ' ', $font_name );
            }
        }

        public function get_element_fonts() {
            $logo_font      = $this->fonts[ 'logo_font' ];
            $menu_font      = $this->fonts[ 'menu_font' ];
            $title_font     = $this->fonts[ 'title_font' ];
            $post_meta_font = $this->fonts[ 'post_meta_font' ];
            $content_font   = $this->fonts[ 'content_font' ];
            $footer_font    = $this->fonts[ 'footer_font' ];

            return ".logo { Font-Family: {$logo_font}, serif }" . ".main-navigation, .hamburger { Font-Family: {$menu_font}, serif }" . "#menu-amp-menu { Font-Family: {$menu_font}, serif }" . ".amp-menu-sidebar { Font-Family: {$menu_font}, serif }" . "amp-sidebar .menu { Font-Family: {$menu_font}, serif }" . ".amphtml-title { Font-Family: {$title_font}, serif }" . ".amphtml-meta-author { Font-Family: {$post_meta_font}, serif }" . ".page-header, .amphtml-content, .breadcrumb li, #pagination { Font-Family: {$content_font}, serif }" . ".footer { Font-Family: {$footer_font}, serif }";
        }

        public function get_element_colors() {
            $body_bg            = $this->options->get( 'background_color' );
            $add_to_cart_button = $this->options->get( 'add_to_cart_button_color' );

            $header_color      = $this->options->get( 'header_color' );
            $header_text_color = $this->options->get( 'header_text_color' );

            $header_menu_color      = $this->options->get( 'header_menu_color' );
            $header_menu_text_color = $this->options->get( 'header_menu_text_color' );

            $titles_color    = $this->options->get( 'main_title_color' );
            $main_text_color = $this->options->get( 'main_text_color' );

            $link_text       = $this->options->get( 'link_color' );
            $link_text_hover = $this->options->get( 'link_hover' );

            $inputs_color            = $this->options->get( 'inputs_color' );
            $inputs_color_hex_to_rgb = sscanf( $inputs_color, "#%02x%02x%02x" );

            $blockquote_border           = $this->options->get( 'main_title_color' );
            $blockquote_color_hex_to_rgb = sscanf( $blockquote_border, "#%02x%02x%02x" );

            $footer_color      = $this->options->get( 'footer_color' );
            $footer_text_color = $this->options->get( 'footer_text_color' );

            return "body {background:{$body_bg};color:{$main_text_color}}" .
            ".header, .header .hamburger .hamburger-line, .header .hamburger:not(.text-button)[aria-expanded=true]{background-color:{$header_color}}" .
            ".header .logo a{color: {$header_text_color}} .header .hamburger, .header .hamburger[aria-expanded=true] .hamburger-line{background-color:{$header_text_color}}" .
            "amp-sidebar, .header amp-accordion .main-navigation {background-color: {$header_menu_color}}" .
            "amp-sidebar .menu li a, .header amp-accordion .main-navigation ul li a, .header .hamburger {color: {$header_menu_text_color}} amp-sidebar .amp-close-button .close-line, .header amp-accordion .main-navigation ul li:not(:last-of-type) a:before, amp-sidebar .menu li:not(:last-of-type) a:before{background-color: {$header_menu_text_color}}" .
            "h1,h2,h3,h4,h5,h6, .product-total-price, .cart-totals-column-price{color:{$titles_color}} #main h1.amphtml-title::before{background-color:{$titles_color}}" .
            "a, nav.breadcrumb ul li, .amphtml-content ul:not([class]) li::before, .amphtml-single-post ul:not([class]) li::before, .amphtml-single-product .star, .products .product-card .star, .related-products-container .wc-related-start, .amphtml-readmore, .amp-reviews > li article .comment-meta .comment-author b{color:{$link_text}} a:hover{color:{$link_text_hover}}" .
            ".amphtml-single-post blockquote::before {background-color: {$blockquote_border}}" .
            ".amphtml-single-post blockquote {background-color: rgba({$blockquote_color_hex_to_rgb[ 0 ]}, {$blockquote_color_hex_to_rgb[ 1 ]}, {$blockquote_color_hex_to_rgb[ 2 ]}, 0.1)}" .
            ".amphtml-single-product amp-accordion h4, .wpcf7 .wpcf7-submit {color: {$link_text}} .amp-button-holder .amp-button, .amphtml-single-product amp-accordion h4[aria-expanded=true],.amphtml-single-product amp-accordion h4::before,.amphtml-single-product amp-accordion h4::after,.icon-arrow-left:before,.icon-arrow-right::before,.icon-arrow-left::after,.icon-arrow-right::after {background-color: {$link_text}} .amphtml-single-product amp-accordion h4, .amphtml-readmore, .amp-reviews > li ul.children > li,#pagination .prev a, #pagination .next a, .wpcf7 .wpcf7-submit {border-color:{$link_text}}" .
            "input[type=text],input[type=email],input[type=password],input[type=number],input[type=submit],input[type=search],input[type=tel], select, textarea{color:{$inputs_color};border-color:{$inputs_color}}" .
            "input::-webkit-input-placeholder{color:{$inputs_color}} input::-moz-placeholder{color:{$inputs_color}} input:-ms-input-placeholder{color:{$inputs_color}} input:-moz-placeholder{color:{$inputs_color}} input:placeholder{color:{$inputs_color}} .search-submit {background-color: {$inputs_color}}.wpcf7 .ampcf7-loading::before{border-color: {$inputs_color} {$inputs_color} {$inputs_color} transparent}" .
            ".amphtml-single-product .amphtml-add-to .a-button, .cart .amphtml-add-to .a-button, .products .product .onsale, .products .product-card .amphtml-title::after, .products .product woocommerce-loop-product__title::after, .related-products-container .wc-related-name::after {background-color: {$add_to_cart_button}} .products .product-card .amphtml-add-to .a-button, .cart-actions button {border-color:{$add_to_cart_button}} .products-list_2 .product-card {box-shadow: 0px 0px 1px {$add_to_cart_button}} .cart-products, .cart-products .cart-product, .cart-product .cart-product-image,.cart-actions,.cart-totals,.cart-totals .cart-totals-row {border-color: rgba({$inputs_color_hex_to_rgb[ 0 ]}, {$inputs_color_hex_to_rgb[ 1 ]}, {$inputs_color_hex_to_rgb[ 2 ]}, 0.4)} .products .product-card .amphtml-add-to .a-button, .cart-actions button{color:{$add_to_cart_button}}" .
            ".footer { background: {$footer_color}; }" .
            ".footer, .footer a { color: {$footer_text_color} }";
        }

        public function get_custom_css() {
            $content_width      = absint( $this->options->get( 'content_width' ) );
            $main_content_width = $content_width + 20;

            $custom_fonts = $this->options->get( 'custom_fonts' );
            if ( ! empty( $custom_fonts ) ) {
                foreach ( $custom_fonts as $custom_font ) {
                    if ( ! empty( $custom_font[ 'name' ] ) && ! empty( $custom_font[ 'link' ] ) ) {
                        echo '@font-face {font-family: "' . $custom_font[ 'name' ] . '";src: url("' . $custom_font[ 'link' ] . '");}';
                    }
                    if ( ! empty( $custom_font[ 'name' ] ) && ! empty( $custom_font[ 'link_bold' ] ) ) {
                        echo '@font-face {font-family: "' . $custom_font[ 'name' ] . '";src: url("' . $custom_font[ 'link_bold' ] . '");font-weight: bold;}';
                    }
                }
            }
            echo PHP_EOL . "amp-accordion .menu, .footer .inner { max-width: {$content_width}px; margin: 0 auto;}" . "#main .inner { max-width: {$main_content_width}px; } " . $this->get_element_fonts() . $this->get_element_colors();
        }

        public function the_template_content() {
            echo $this->render( $this->template_content );
        }

        public function set_template_content( $template ) {
            $this->template_content = $template;
            add_action( 'amphtml_template_content', array( $this, 'the_template_content' ) );

            return $this;
        }

        public function get_minify_style_path( $filename ) {
            $path = $this->get_dir_path( self::STYLE_DIR ) . DIRECTORY_SEPARATOR . $filename . '.min.css';

            return file_exists( $path ) ? $path : '';
        }

        public function get_style( $filename ) {
            $styles = '';
            $path   = '';
            
            $path = $this->get_dir_path( self::STYLE_DIR ) . DIRECTORY_SEPARATOR . $filename . '.min.css';

            if ( file_exists( $path ) ) {
                $styles = file_get_contents( $path );
            } else {
                $this->generate_minified_css_file( $filename );
            }

            return apply_filters( 'amphtml_style', $styles, $this );
        }

        public function generate_minified_css_file( $file ) {
            $minified_fiel_path = $this->get_dir_path( self::STYLE_DIR ) . DIRECTORY_SEPARATOR . $file . '.min.css';

            $minified_style_file = fopen( $minified_fiel_path, 'w' );

            $path = $this->get_dir_path( self::STYLE_DIR ) . DIRECTORY_SEPARATOR . $file . '.css';

            $styles = $this->minify_css( $styles = file_get_contents( $path ) );
            fwrite( $minified_style_file, $styles );
            fclose( $minified_style_file );
        }

        public function get_title( $id ) {
            return get_the_title( $id );
        }

        public function get_content( $post ) {
            $content = $post->post_content;

            if ( $this->options->get( 'default_the_content' ) ) {
                $this->remove_custom_the_content_hooks();
            }

            return apply_filters( 'the_content', $content );
        }

        public function remove_custom_the_content_hooks() {
            global $wp_filter;

            $hooks    = $wp_filter[ 'the_content' ];
            $defaults = $this->get_default_the_content_hooks();

            if ( class_exists( 'WP_Hook' ) ) {
                $hooks = $hooks->callbacks;
            }

            foreach ( $hooks as $priority => $functions ) {

                foreach ( $functions as $name => $function ) {

                    $function_name = ( is_array( $function[ 'function' ] ) ) ? $function[ 'function' ][ 1 ] : $function[ 'function' ];

                    if ( ! isset( $defaults[ $priority ] ) || ! in_array( $function_name, $defaults[ $priority ] ) ) {
                        if ( isset( $wp_filter[ 'the_content' ] ) ) {
                            if ( class_exists( 'WP_Hook' ) ) {
                                unset( $wp_filter[ 'the_content' ]->callbacks[ $priority ][ $name ] );
                            } else {
                                unset( $wp_filter[ 'the_content' ][ $priority ][ $name ] );
                            }
                        }
                    }
                }

                if ( ! count( $wp_filter[ 'the_content' ][ $priority ] ) ) {
                    unset( $wp_filter[ 'the_content' ][ $priority ] );
                }
            }
        }

        public function get_default_the_content_hooks() {
            return apply_filters( 'amphtml_the_content', array(
                '11' => array( 'capital_P_dangit', 'do_shortcode' ),
                '10' => array(
                    'wptexturize',
                    'convert_smilies',
                    'wpautop',
                    'shortcode_unautop',
                    'prepend_attachment',
                    'wp_make_content_images_responsive',
                    'amphtml_shortcode_fix',
                    'amphtml_content_ads',
                ),
                '8'  => array( 'run_shortcode', 'autoembed' ),
            ) );
        }

        public function set_post( $id, $set_meta = true ) {

            // Image gallery just for single post
            add_shortcode( 'gallery', array( $this, 'gallery_shortcode' ) );

            $this->post               = get_post( $id );
            $this->ID                 = $this->post->ID;
            $this->title              = $this->get_title( $this->ID );
            $this->publish_timestamp  = get_the_date( 'U', $this->ID );
            $this->modified_timestamp = get_post_modified_time( 'U', false, $this->post );
            $this->author             = get_userdata( $this->post->post_author );
            $this->content            = $this->get_content( $this->post );
            $this->content            = apply_filters( 'amphtml_single_content', $this->content );
            $this->content            = $this->sanitizer->sanitize_content( $this->content );
            $this->content            = $this->multipage_content( $this->content );
            $this->featured_image     = $this->get_featured_image();

            if ( $set_meta ) {
                $this->metadata = $this->get_schema_metadata( $this->post, $this->get_post_excerpt_by_id( $id ) );
            }
        }

        public function set_archive_page_post( $id ) {
            $this->post               = get_post( $id );
            $this->ID                 = $this->post->ID;
            $this->title              = $this->get_title( $this->ID );
            $this->publish_timestamp  = get_the_date( 'U', $this->ID );
            $this->modified_timestamp = get_post_modified_time( 'U', false, $this->post );
            $this->author             = get_userdata( $this->post->post_author );
            $this->content            = $this->get_content( $this->post );
            $this->featured_image     = $this->get_featured_image();
            $this->description        = $this->get_archive_excerpt();
        }

        public function get_archive_excerpt() {
            if ( ! empty( $this->post->post_excerpt ) ) {
                $_excerpt = $this->post->post_excerpt;
            } else {
                $_excerpt = $this->content;
            }

            return apply_filters( 'amphtml_archive_excerpt', $_excerpt );
        }

        public function get_archive_page_description() {
            $post_link = $this->get_amphtml_link( get_permalink() );
            $read_mode = ' ... <a class="amphtml-readmore" href="' . $post_link . '">' . esc_html__( 'Read more', 'amphtml' ) . '</a>';

            return wp_trim_words( $this->description, $this->options->get( 'length_excerpt' ), $read_mode );
        }

        public function set_schema_metadata( $post = null, $description = '' ) {
            global $wp_query, $wp;

            if ( $description ) {
                $metadata[ 'description' ] = $description;
            }

            foreach ( $wp_query->posts as $post ) {
                $excerpt    = apply_filters( 'get_the_excerpt', $post->post_excerpt, $post );
                $metadata[] = $this->get_schema_metadata( $post, $excerpt );
            }

            $this->metadata = $metadata;
        }

        public function get_schema_metadata( $post, $description = '' ) {
            $author        = get_userdata( $post->post_author );
            $post_image_id = $this->get_post_image_id( $post->ID );
            $logo          = $this->default_logo;

            if ( empty( $logo ) ) {
                $logo = $this->logo;
            }

            if ( $this->options->get( 'schema_type' ) != 'LegalService' ) {
                $metadata = array(
                    '@context'         => 'http://schema.org',
                    '@type'            => apply_filters( 'amphtml_schema_type', $this->options->get( 'schema_type' ), $this ),
                    'headline'         => ! empty( $this->get_title( $post->ID ) ) ? $this->get_title( $post->ID ) : 'untitled',
                    'url'              => get_permalink( $post->ID ),
                    'datePublished'    => $post ? date( 'c', get_the_date( 'U', $post->ID ) ) : date( 'c' ),
                    'dateModified'     => $post ? date( 'c', get_post_modified_time( 'U', false, $post ) ) : date( 'c' ),
                    'mainEntityOfPage' => array(
                        '@type' => 'WebPage',
                        '@id'   => $post ? get_permalink( $post->ID ) : get_bloginfo( 'url' ),
                    ),
                    'publisher'        => array(
                        '@type' => 'Organization',
                        'name'  => $this->blog_name,
                    ),
                    'author'           => array(
                        '@type' => 'Person',
                        'name'  => $post ? $author->display_name : 'admin',
                    ),
                    'image'            => $post ? $this->get_schema_images( $post_image_id ) : ''
                );

                if ( $description ) {
                    $metadata[ 'description' ] = wp_strip_all_tags( $description );
                }

                if ( $logo ) {
                    if ( $img_obj = json_decode( $logo ) ) {
                        $metadata[ 'publisher' ][ 'logo' ] = array(
                            '@type'  => 'ImageObject',
                            'url'    => $img_obj->url,
                            'height' => $img_obj->height,
                            'width'  => $img_obj->width,
                        );
                    } else {
                        $attachment_id = $this->get_attachment_id_from_url( $logo );
                        $logo_arr      = wp_get_attachment_metadata( $attachment_id );
                        if ( ! is_array( $logo_arr ) ) {
                            $height = 60;
                            $width  = 600;
                        } else {
                            $height = $logo_arr[ 'height' ];
                            $width  = $logo_arr[ 'width' ];
                        }
                        $metadata[ 'publisher' ][ 'logo' ] = array(
                            '@type'  => 'ImageObject',
                            'url'    => $logo,
                            'height' => $height,
                            'width'  => $width,
                        );
                    }
                }
            } else {
                $metadata = array(
                    '@context'                  => 'http://schema.org',
                    '@type'                     => apply_filters( 'amphtml_schema_type', $this->options->get( 'schema_type' ), $this ),
                    'name'                      => $this->options->get( 'legal_service_name' ),
                    'telephone'                 => $this->options->get( 'legal_service_telephone' ),
                    'url'                       => get_bloginfo( 'url' ),
                    'priceRange'                => $this->options->get( 'legal_service_price_range' ),
                    'address'                   => array(
                        '@type'           => 'PostalAddress',
                        'streetAddress'   => $this->options->get( 'legal_service_street_address' ),
                        'addressLocality' => $this->options->get( 'legal_service_address_locality' ),
                        'postalCode'      => $this->options->get( 'legal_service_postal_code' ),
                        'addressRegion'   => $this->options->get( 'legal_service_address_region' ),
                        'addressCountry'  => array(
                            '@type' => 'Country',
                            'name'  => $this->options->get( 'legal_service_address_country' ),
                        )
                    ),
                    'openingHoursSpecification' => array(
                        '@type'     => 'OpeningHoursSpecification',
                        'dayOfWeek' => $this->options->get( 'legal_service_open_days' ),
                        'opens'     => $this->options->get( 'legal_service_opens' ),
                        'closes'    => $this->options->get( 'legal_service_closes' ),
                    ),
                    'contactPoint'              => array(
                        '@type'             => 'ContactPoint',
                        'contactType'       => $this->options->get( 'contact_point_contact_type' ),
                        'telephone'         => $this->options->get( 'contact_point_telephone' ),
                        'url'               => $this->options->get( 'contact_point_page_url' ),
                        'email'             => $this->options->get( 'contact_point_email' ),
                        'areaServed'        => $this->options->get( 'contact_point_area_served' ),
                        'availableLanguage' => $this->options->get( 'contact_point_available_language' ),
                    ),
                    'image'                     => $post ? $this->get_schema_images( $post_image_id ) : ''
                );
            }

            return apply_filters( 'amphtml_metadata', $metadata, $post );
        }

        /**
         * Get attachment ID from URL
         *
         * @global type $wpdb
         *
         * @param string $attachment_url
         *
         * @return int
         */
        function get_attachment_id_from_url( $attachment_url = '' ) {
            global $wpdb;
            $attachment_id = false;
            if ( '' == $attachment_url ) {
                return;
            }
            $upload_dir_paths = wp_upload_dir();
            // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
            if ( false !== strpos( $attachment_url, $upload_dir_paths[ 'baseurl' ] ) ) {
                $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
                $attachment_url = str_replace( $upload_dir_paths[ 'baseurl' ] . '/', '', $attachment_url );
                $attachment_id  = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
            }

            return $attachment_id;
        }

        public function get_attachment_id_from_src( $image_src ) {
            global $wpdb;
            $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";

            return $wpdb->get_var( $query );
        }

        public function get_post_image_id( $post_id ) {
            $post_image_id = $this->get_post_thumbnail_id( $post_id );
            $logo          = $this->options->get( 'default_image' );

            if ( $img_obj = json_decode( $logo ) ) {
                $default_image = $img_obj->id;
            } else {
                $default_image = $this->get_attachment_id_from_src( $logo );
            }

            if ( $post_image_id ) {
                $img_info = wp_get_attachment_metadata( $post_image_id );
                if ( ! empty( $default_image ) && $img_info[ 'width' ] > 1199 ) {
                    return $post_image_id;
                }
            }

            $image_ids = get_posts( array(
                'post_parent'      => $post_id,
                'post_type'        => 'attachment',
                'post_mime_type'   => 'image',
                'posts_per_page'   => 1,
                'orderby'          => 'menu_order',
                'order'            => 'ASC',
                'fields'           => 'ids',
                'suppress_filters' => false,
            ) );
            foreach ( $image_ids as $image_id ) {
                $img_info = wp_get_attachment_metadata( $image_id );
                if ( empty( $default_image ) && $img_info[ 'width' ] > 1199 ) {
                    return $image_id;
                }
            }
            if ( ! empty( $default_image ) ) {
                $post_image_id = $default_image;
            }

            return $post_image_id;
        }

        public function get_post_thumbnail_id( $post_id ) {
            $thumbnail_id = get_post_meta( $post_id, 'amphtml_featured_image_id', true );

            if ( ! $thumbnail_id ) {
                $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
            }

            return $thumbnail_id;
        }

        public function get_schema_images( $post_image_id ) {
            $post_image_src = wp_get_attachment_image_src( $post_image_id, 'full' );

            if ( is_array( $post_image_src ) ) {
                return array(
                    '@type'  => 'ImageObject',
                    'url'    => $post_image_src[ 0 ],
                    'width'  => ( $post_image_src[ 1 ] > self::SCHEMA_IMG_MIN_WIDTH ) ? $post_image_src[ 1 ] : self::SCHEMA_IMG_MIN_WIDTH,
                    'height' => $post_image_src[ 2 ],
                );
            }

            return array(
                '@type' => 'ImageObject'
            );
        }

        public function get_post_excerpt_by_id( $post_id ) {
            global $post;
            $post        = get_post( $post_id );
            setup_postdata( $post );
            $the_excerpt = get_the_excerpt();
            wp_reset_postdata();

            return $the_excerpt;
        }

        public function get_featured_image() {
            $featured_image    = '';
            $image_id          = get_post_meta( $this->ID, 'amphtml_featured_image_id', true );
            $post_thumbnail_id = ( $image_id ) ? $image_id : get_post_thumbnail_id( $this->ID );

            if ( $post_thumbnail_id ) {
                $size                    = apply_filters( 'amphtml_featured_image_size', 'amphtml-image', $this->options );
                $featured_image          = wp_get_attachment_image_src( $post_thumbnail_id, $size );
                $featured_image[ 'alt' ] = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );

                // Check image dimensions
                if ( empty( $featured_image[ 1 ] ) ) {
                    // Check width
                    $featured_image[ 1 ] = $this->get_option( 'content_width' );
                } else if ( empty( $featured_image[ 2 ] ) ) {
                    // Check height
                    $featured_image[ 2 ] = $this->get_option( 'element_height' );
                }
            }

            return $featured_image;
        }

        public function nav_menu() {
            $nav_menu = wp_nav_menu( array(
                'theme_location' => $this->options->get( 'amphtml_menu' ),
                'echo'           => false
            ) );
            $nav_menu = $this->sanitizer->sanitize_content( $nav_menu );
            return apply_filters( 'amphtml_nav_menu', $nav_menu );
        }

        public function nav_menu_footer() {
            $nav_menu = wp_nav_menu( array(
                'theme_location' => $this->options->get( 'amphtml_menu_footer' ),
                'echo'           => false
            ) );
            $nav_menu = $this->sanitizer->sanitize_content( $nav_menu );
            return apply_filters( 'amphtml_nav_menu_footer', $nav_menu );
        }

        public function update_menu_item_url( $item ) {
            $avoid_amp_class = apply_filters( 'amphtml_no_amp_menu_link', 'no-amp' );

            if ( 'custom' != $item->object && false === array_search( $avoid_amp_class, $item->classes ) ) {
                $id        = ( $item->type == 'taxonomy' ) ? '' : $item->object_id;
                $item->url = $this->get_amphtml_link( $item->url, $id );
            }

            return $item;
        }

        public function get_amphtml_link( $link, $id = '' ) {
            return $this->options->get_amphtml_link( $link, $id );
        }

        public function get_logo_link() {
            $arg = apply_filters( 'amphtml_logo_link', false );

            if ( $arg == false ) {
                echo esc_url( $this->get_amphtml_link( $this->base_url ) );
            } else {
                echo $arg;
            }
        }

        public function gallery_shortcode( $attr ) {
            $size = $this->get_default_image_size();

            add_image_size( 'amphtml-size', $size[ 'width' ], $size[ 'height' ] );

            $sanitizer = $this->sanitizer;
            $gallery   = gallery_shortcode( $attr );

            $attr = shortcode_atts( array(
                'size' => 'amphtml-size'
            ), $attr );

            $sanitizer->load_content( $gallery );

            $image_size = $this->get_image_size( $attr[ 'size' ] );

            if ( empty( $image_size ) ) {
                $image_size = $this->get_default_image_size();
            }

            $gallery_images  = $sanitizer->get_amp_images( $image_size );
            $gallery_content = $this->render_element( 'carousel', array(
                'width'  => $image_size[ 'width' ],
                'height' => $image_size[ 'height' ],
                'images' => $gallery_images
            ) );

            return $gallery_content;
        }

        public function get_default_image_size() {
            $size             = array();
            $size[ 'width' ]  = $this->options->get( 'content_width' );
            $size[ 'height' ] = round( $size[ 'width' ] / ( 16 / 9 ), 0 );

            return $size;
        }

        public function get_image_size( $size ) {
            $sizes = $this->get_image_sizes();

            if ( isset( $sizes[ $size ] ) ) {
                return $sizes[ $size ];
            }

            return false;
        }

        public function get_image_sizes() {
            global $_wp_additional_image_sizes;

            $sizes = array();

            foreach ( get_intermediate_image_sizes() as $_size ) {
                if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                    $sizes[ $_size ][ 'width' ]  = get_option( "{$_size}_size_w" );
                    $sizes[ $_size ][ 'height' ] = get_option( "{$_size}_size_h" );
                    $sizes[ $_size ][ 'crop' ]   = (bool) get_option( "{$_size}_crop" );
                } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                    $sizes[ $_size ] = array(
                        'width'  => $_wp_additional_image_sizes[ $_size ][ 'width' ],
                        'height' => $_wp_additional_image_sizes[ $_size ][ 'height' ],
                        'crop'   => $_wp_additional_image_sizes[ $_size ][ 'crop' ],
                    );
                }
            }

            return $sizes;
        }

        public function render_element( $template, $element ) {
            $template_path = $this->get_template_path( $template );

            if ( file_exists( $template_path ) ) {
                ob_start();
                include( $template_path );

                return ob_get_clean();
            }
        }

        public function get_post_meta() {
            $meta = ( $this->options->get( 'post_meta_author' ) ) ? $this->render( 'meta-author' ) : '';
            $meta .= $this->render( 'meta-time' );
            $meta .= ( $this->options->get( 'post_meta_categories' ) ) ? $this->render( 'meta-cats' ) : '';
            $meta .= ( $this->options->get( 'post_meta_tags' ) ) ? $this->render( 'meta-tags' ) : '';

            return apply_filters( 'amphtml_meta', $meta, $this );
        }

        public function update_meta_links( $termlink ) {
            return $this->get_amphtml_link( $termlink );
        }

        public function is_featured_image() {
            global $wp_query;

            if ( ! $this->featured_image ) {
                return false;
            }
            return ( ( is_archive() && $this->options->get( 'archive_featured_image' ) ) || ( is_page() && $this->options->get( 'page_featured_image' ) ) || ( is_home() && ! is_front_page() && ! $wp_query->is_posts_page && $this->options->get( 'page_featured_image' ) ) || ( is_single() && $this->options->get( 'post_featured_image' ) ) || ( is_search() && $this->options->get( 'search_featured_image' ) ) || ( is_home() && is_front_page() && $this->options->get( 'blog_featured_image' ) ) || ( $wp_query->is_posts_page && $this->options->get( 'blog__featured_image' ) ) );
        }

        public function is_enabled_meta() {
            global $wp_query;

            return ( ( is_search() && $this->options->get( 'search_meta' ) ) || ( is_archive() && $this->options->get( 'archive_meta' ) ) || ( is_home() && is_front_page() && $this->options->get( 'blog_meta' ) ) || ( $wp_query->is_posts_page && $this->options->get( 'blog_meta' ) ) );
        }

        public function is_enabled_excerpt() {
            global $wp_query;

            return ( ( is_search() && $this->options->get( 'search_excerpt' ) ) || ( is_archive() && $this->options->get( 'archive_excerpt' ) ) || ( is_home() && is_front_page() && $this->options->get( 'blog_excerpt' ) ) || ( $wp_query->is_posts_page && $this->options->get( 'blog_excerpt' ) ) );
        }

        public function update_pagination_link( $pagination_link ) {
            $endpoint = AMPHTML()->get_endpoint();
            $pattern  = "/(\/page\/\d+)?\/($endpoint)(\/page\/\d+)?\/?(\?.*)?\/?$/";

            preg_match( $pattern, $pagination_link, $matches );

            if ( false === isset( $matches[ 2 ] ) ) {
                return $pagination_link;
            }

            $pagination_link = str_replace( $matches[ 0 ], '', $pagination_link );

            $page = isset( $matches[ 3 ] ) ? $matches[ 3 ] : '';

            $pagination_link = $pagination_link . $page . '/' . $matches[ 2 ] . '/' . $matches[ 4 ];

            return $pagination_link;
        }

        public function get_canonical_url() {
            return AMPHTML()->get_canonical_url();
        }

        public function get_permalink() {
            global $wp;

            return home_url( add_query_arg( array(), $wp->request ) );
        }

        public function set_blocks( $type, $default = true ) {
            $this->blocks = $this->options->get_template_elements( $type, $default );
        }

        public function get_blocks() {
            return $this->blocks;
        }

        public function set_section( $type ) {
            $this->section = $type;
        }

        public function get_section() {
            return $this->section;
        }

        /**
         * Block posts title
         *
         * @param string $type block type
         *
         * @return string
         */
        public function get_posts_title( $type ) {
            return esc_html__( $this->get_option( $this->get_section() . '_' . $type . '_title' ) );
        }

        /**
         * Get posts count option
         *
         * @param string $type option type
         *
         * @return int posts count to render
         */
        public function get_posts_count( $type ) {
            return $this->get_option( $this->get_section() . '_' . $type . '_count' );
        }

        /**
         * Is show amp post thumbnail
         *
         * @param string $type
         *
         * @return bolean
         */
        public function get_posts_thumbnail( $type ) {
            return $this->get_option( $this->get_section() . '_' . $type . '_thumbnail' ) ? true : false;
        }

        /**
         * Render amp post thumbnail in list
         *
         * @param int $post_id
         *
         * @return empty string if no any thumbnail
         */
        public function the_post_thumbnail_tpl( $post_id ) {
            $thumb_id = (int) get_post_meta( $post_id, 'amphtml_featured_image_id', true );
            $thumb_id = $thumb_id ? $thumb_id : (int) get_post_thumbnail_id( $post_id );
            if ( $thumb_id ) {
                $img = wp_get_attachment_image_src( $thumb_id, 'amphtml-image' );
                if ( is_array( $img ) ) {
                    echo "<div class='wp-amp-recent-thumb'>" . "<amp-img src='{$img[ 0 ]}' width='{$img[ 1 ]}' height='{$img[ 2 ]}' layout='responsive'>" . "</amp-img></div>";
                }
            }

            return '';
        }

        public function get_related_posts( $post, $count = 2 ) {
            $taxs = get_object_taxonomies( $post );
            if ( ! $taxs ) {
                return '';
            }

            // ignoring post formats
            if ( ( $key = array_search( 'post_format', $taxs ) ) !== false ) {
                unset( $taxs[ $key ] );
            }

            // try tags first
            if ( ( $tag_key = array_search( 'post_tag', $taxs ) ) !== false ) {

                $tax          = 'post_tag';
                $tax_term_ids = wp_get_object_terms( $post->ID, $tax, array( 'fields' => 'ids' ) );
            }

            // if no tags, then by cat or custom tax
            if ( empty( $tax_term_ids ) ) {
                // remove post_tag to leave only the category or custom tax
                if ( $tag_key !== false ) {
                    unset( $taxs[ $tag_key ] );
                    $taxs = array_values( $taxs );
                }

                $tax          = $taxs[ 0 ];
                $tax_term_ids = wp_get_object_terms( $post->ID, $tax, array( 'fields' => 'ids' ) );
            }

            if ( $tax_term_ids ) {
                $args    = array(
                    'post_type'      => $post->post_type,
                    'posts_per_page' => $count,
                    'orderby'        => 'rand',
                    'post_status'    => 'publish',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => $tax,
                            'field'    => 'id',
                            'terms'    => $tax_term_ids
                        )
                    ),
                    'post__not_in'   => array( $post->ID ),
                );
                $related = new WP_Query( $args );

                return $related;
            }
        }

        public function get_recent_posts( $count ) {
            return new WP_Query( array(
                'orderby'             => 'date',
                'posts_per_page'      => $count,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true
            ) );
        }

        public function get_doc_title() {
            return $this->doc_title;
        }

        public function get_footer() {
            $footer_content = apply_filters( 'amphtml_template_footer', $this->options->get( 'footer_content' ) );
            $footer_content = esc_html__( $footer_content, 'amphtml' );
            if ( $footer_content ) {
                $footer_content = do_shortcode( $footer_content );
                $footer_content = $this->sanitizer->sanitize_content( $footer_content )->save();
            }

            return apply_filters( 'amphtml_template_footer_content', $footer_content );
            ;
        }

        public function get_scrolltop() {
            return $this->options->get( 'footer_scrolltop' );
        }

        public function get_social_buttons() {
            return $this->options->get( 'footer_social' );
        }

        public function load() {
            $social_share_script = array(
                'slug' => 'amp-social-share',
                'src'  => 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js'
            );
            $social_like_script  = array(
                'slug' => 'amp-facebook-like',
                'src'  => 'https://cdn.ampproject.org/v0/amp-facebook-like-0.1.js'
            );

            $is_loaded = apply_filters( 'amphtml_template_load', false, $this );

            if ( $is_loaded ) {
                return $this;
            }

            switch ( true ) {
                case is_front_page() && is_home():
                    $this->set_template_content( 'archive' );
                    $this->set_blocks( 'blog' );
                    $this->set_section( 'blog' );
                    $this->set_schema_metadata();
                    break;
                case is_front_page():
                    $this->set_template_content( 'single-content' );
                    $current_post_id = get_option( 'page_on_front' );
                    $this->set_post( $current_post_id );
                    $this->set_blocks( 'page' );
                    $this->set_section( 'page' );
                    if ( $this->options->get( 'page_social_share' ) ) {
                        $this->add_embedded_element( $social_share_script );
                    }
                    if ( $this->options->get( 'social_like_button' ) && $this->options->get( 'page_social_share' ) ) {
                        $this->add_embedded_element( $social_like_script );
                    }
                    break;
                case is_home():
                    $this->set_template_content( 'archive' );
                    $this->set_blocks( 'blog' );
                    $this->set_section( 'blog' );
                    $this->set_schema_metadata();
                    break;
                case is_single():
                    $this->set_template_content( 'single-content' );
                    $current_post_id = get_the_ID();
                    $post_type       = get_post_type( $current_post_id );
                    $post_type       = apply_filters( 'amphtml_post_type', 'post', $current_post_id );
                    $this->set_post( $current_post_id );
                    $this->set_blocks( $post_type );
                    $this->set_section( $post_type );
                    if ( $this->options->get( $post_type . '_social_share' ) ) {
                        $this->add_embedded_element( $social_share_script );
                    }
                    if ( $this->options->get( 'social_like_button' ) && $this->options->get( $post_type . '_social_share' ) ) {
                        $this->add_embedded_element( $social_like_script );
                    }
                    break;
                case is_page():
                    $this->set_template_content( 'single-content' );
                    $current_post_id = get_the_ID();
                    $this->set_post( $current_post_id );
                    $this->set_blocks( 'page' );
                    $this->set_section( 'page' );
                    if ( $this->options->get( 'page_social_share' ) ) {
                        $this->add_embedded_element( $social_share_script );
                    }
                    if ( $this->options->get( 'social_like_button' ) && $this->options->get( 'page_social_share' ) ) {
                        $this->add_embedded_element( $social_like_script );
                    }
                    break;
                case is_search():
                    $this->set_template_content( 'archive' );
                    $this->set_blocks( 'search' );
                    $this->set_section( 'search' );
                    $this->title = esc_html__( 'Search Results', 'amphtml' );
                    $this->set_schema_metadata();
                    break;
                case is_archive():
                    $this->set_template_content( 'archive' );
                    $this->set_blocks( 'archive' );
                    $this->set_section( 'archive' );
                    $this->title = get_the_archive_title();
                    $this->set_schema_metadata( get_the_archive_description() );
                    break;
                case is_404():
                    $this->set_template_content( 'single-content' );
                    $this->set_blocks( '404' );
                    $this->set_section( '404' );
                    break;
            }
        }

        public function get_image_size_from_url( $url ) {
            $image = new FastImage( $url );
            list( $size[ 'width' ], $size[ 'height' ] ) = $image->getSize();

            return $size;
        }

        public function get_sanitize_obj() {
            return $this->sanitizer;
        }

        public function get_template_name( $element ) {
            $name = '';
            if ( $this->options->get( $element ) ) {
                switch ( $element ) {
                    case false !== strpos( $element, '_ad_' ):
                        $name          = $this->set_ad_data( $element ) ? 'ad' : '';
                        break;
                    case false !== strpos( $element, 'custom_html' ):
                        $name          = $this->set_custom_html( $element ) ? 'custom_html' : '';
                        break;
                    default:
                        $template_name = $this->options->get( $element, 'template_name' );
                        $name          = $template_name ? $template_name : $element;
                        break;
                }
            }

            return apply_filters( 'amphtml_template_name', $name, $element, $this );
        }

        public function set_ad_data( $element ) {
            $element = explode( '_ad_', $element );
            $ad_num  = array_pop( $element );

            $this->ad = array(
                'data_client'  => $this->options->get( "ad_data_id_client_$ad_num" ),
                'data_ad_slot' => $this->options->get( "ad_adsense_data_slot_$ad_num" ),
                'type'         => $this->options->get( "ad_type_$ad_num" ),
                'width'        => $this->options->get( "ad_width_$ad_num" ),
                'height'       => $this->options->get( "ad_height_$ad_num" ),
                'layout'       => $this->options->get( "ad_layout_$ad_num" ),
                'data_slot'    => $this->options->get( "ad_doubleclick_data_slot_$ad_num" ),
                'custom_code'  => $this->options->get( "ad_content_code_$ad_num" )
            );

            if ( $this->ad[ 'type' ] == 'adsense_auto' ) {
                $this->adsense_auto = $this->ad[ 'data_client' ];
                add_action( 'amphtml_after_footer', array( $this, 'add_auto_ads' ) );
            }

            return $this->ad[ 'type' ];
        }

        public function set_custom_html( $element ) {
            return $this->custom_html = $this->options->get( $element );
        }

        public function get_comments_template_path( $path ) {
            $name = $this->get_template_path( 'comments' );

            return $name;
        }

        public function minify_css( $buffer ) {
            $buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );
            $buffer = str_replace( ': ', ':', $buffer );
            $buffer = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $buffer );

            return ( $buffer );
        }

        public function remove_term_link_filter() {
            remove_filter( 'term_link', array( $this, 'update_meta_links' ) );
        }

        public function get_menu_handler( $name ) {
            $handlers = array(
                'sidebar'   => array(
                    'slug' => 'amp-sidebar',
                    'src'  => 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js'
                ),
                'accordion' => array(
                    'slug' => 'amp-accordion',
                    'src'  => 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js'
                )
            );

            return isset( $handlers[ $name ] ) ? $handlers[ $name ] : '';
        }

        // TODO: find better implementation of breadcrumbs
        function get_breadcrumbs() {
            $text[ 'home' ]     = esc_html__( 'Home', 'amphtml' );
            $text[ 'category' ] = esc_html__( 'Archive by Category "%s"', 'amphtml' );
            $text[ 'search' ]   = esc_html__( 'Search Results for "%s" Query', 'amphtml' );
            $text[ 'tag' ]      = esc_html__( 'Posts Tagged "%s"', 'amphtml' );
            $text[ 'author' ]   = esc_html__( 'Articles Posted by %s', 'amphtml' );
            $text[ '404' ]      = esc_html__( 'Error 404', 'amphtml' );
            $text[ 'page' ]     = esc_html__( 'Page %s', 'amphtml' );
            $text[ 'cpage' ]    = esc_html__( 'Comment Page %s', 'amphtml' );

            $endpoint       = AMPHTML()->get_endpoint();
            $wrap_before    = '<nav class="breadcrumb"><ul>';
            $wrap_after     = '</ul></nav>';
            $show_home_link = 1;        // 1 - show the 'Home' link, 0 - don't show
            $show_current   = 1;        // 1 - show current page title, 0 - don't show
            $before         = '<li>';   // tag before the current crumb
            $after          = '</li>';  // tag after the current crumb

            global $post;
            $home_url     = user_trailingslashit( home_url( "/$endpoint" ) );
            $link_before  = '<li>';
            $link_after   = '</li>';
            $link         = $link_before . '<a href="%1$s">' . '%2$s' . '</a>' . $link_after;
            $frontpage_id = get_option( 'page_on_front' );
            $parent_id    = ( $post ) ? $post->post_parent : '';
            $sep          = '';
            $home_link    = $link_before . '<a href="' . $home_url . '" class="home">' . $text[ 'home' ] . '</a>' . $link_after;
            if ( ! is_front_page() ) {
                echo $wrap_before;
                if ( $show_home_link ) {
                    echo $home_link;
                }
                if ( is_category() ) {
                    $cat = get_category( get_query_var( 'cat' ), false );
                    if ( $cat->parent != 0 ) {
                        $cats = get_category_parents( $cat->parent, true, $sep );
                        $cats = preg_replace( "#^(.+)$sep$#", "$1", $cats );
                        $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1>' . '$2' . '</a>' . $link_after, $cats );
                        if ( $show_home_link ) {
                            echo $sep;
                        }
                        echo $cats;
                    }
                    if ( get_query_var( 'paged' ) ) {
                        $cat = $cat->cat_ID;
                        echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ) ) . $sep . $before . sprintf( $text[ 'page' ], get_query_var( 'paged' ) ) . $after;
                    } else {
                        if ( $show_current ) {
                            echo $sep . $before . sprintf( $text[ 'category' ], single_cat_title( '', false ) ) . $after;
                        }
                    }
                } elseif ( is_search() ) {
                    if ( have_posts() ) {
                        if ( $show_home_link && $show_current ) {
                            echo $sep;
                        }
                        if ( $show_current ) {
                            echo $before . sprintf( $text[ 'search' ], get_search_query() ) . $after;
                        }
                    } else {
                        if ( $show_home_link ) {
                            echo $sep;
                        }
                        echo $before . sprintf( $text[ 'search' ], get_search_query() ) . $after;
                    }
                } elseif ( is_day() ) {
                    if ( $show_home_link ) {
                        echo $sep;
                    }
                    echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $sep;
                    echo sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) );
                    if ( $show_current ) {
                        echo $sep . $before . get_the_time( 'd' ) . $after;
                    }
                } elseif ( is_month() ) {
                    if ( $show_home_link ) {
                        echo $sep;
                    }
                    echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) );
                    if ( $show_current ) {
                        echo $sep . $before . get_the_time( 'F' ) . $after;
                    }
                } elseif ( is_year() ) {
                    if ( $show_home_link && $show_current ) {
                        echo $sep;
                    }
                    if ( $show_current ) {
                        echo $before . get_the_time( 'Y' ) . $after;
                    }
                } elseif ( is_single() && ! is_attachment() ) {
                    if ( $show_home_link ) {
                        echo $sep;
                        if ( $show_current ) {
                            echo $sep . $before . get_the_title() . $after;
                        }
                    } else {
                        $cat  = get_the_category();
                        $cat  = $cat[ 0 ];
                        $cats = get_category_parents( $cat, true, $sep );
                        if ( ! $show_current || get_query_var( 'cpage' ) ) {
                            $cats = preg_replace( "#^(.+)$sep$#", "$1", $cats );
                        }
                        $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1>' . '$2' . '</a>' . $link_after, $cats );
                        echo $cats;
                        if ( get_query_var( 'cpage' ) ) {
                            echo $sep . sprintf( $link, get_permalink(), get_the_title() ) . $sep . $before . sprintf( $text[ 'cpage' ], get_query_var( 'cpage' ) ) . $after;
                        } else {
                            if ( $show_current ) {
                                echo $before . get_the_title() . $after;
                            }
                        }
                    }
                    // custom post type
                } elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
                    $post_type = get_post_type_object( get_post_type() );
                    if ( get_query_var( 'paged' ) ) {
                        echo $sep . sprintf( $link, $this->get_amphtml_link( get_post_type_archive_link( $post_type->name ) ), $post_type->label ) . $sep . $before . sprintf( $text[ 'page' ], get_query_var( 'paged' ) ) . $after;
                    } else {
                        if ( $show_current ) {
                            echo $sep . $before . $post_type->label . $after;
                        }
                    }
                } elseif ( is_attachment() ) {
                    if ( $show_home_link ) {
                        echo $sep;
                    }
                    $parent = get_post( $parent_id );
                    $cat    = get_the_category( $parent->ID );
                    $cat    = $cat[ 0 ];
                    if ( $cat ) {
                        $cats = get_category_parents( $cat, true, $sep );
                        $cats = preg_replace( '#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1>' . '$2' . '</a>' . $link_after, $cats );
                        echo $cats;
                    }
                    printf( $link, get_permalink( $parent ), $parent->post_title );
                    if ( $show_current ) {
                        echo $sep . $before . get_the_title() . $after;
                    }
                } elseif ( is_page() && ! $parent_id ) {
                    if ( $show_current ) {
                        echo $sep . $before . get_the_title() . $after;
                    }
                } elseif ( is_page() && $parent_id ) {
                    if ( $show_home_link ) {
                        echo $sep;
                    }
                    if ( $parent_id != $frontpage_id ) {
                        $breadcrumbs = array();
                        while ( $parent_id ) {
                            $page = get_page( $parent_id );
                            if ( $parent_id != $frontpage_id ) {
                                $breadcrumbs[] = sprintf( $link, get_permalink( $page->ID ), get_the_title( $page->ID ) );
                            }
                            $parent_id = $page->post_parent;
                        }
                        $breadcrumbs = array_reverse( $breadcrumbs );
                        for ( $i = 0; $i < count( $breadcrumbs ); $i ++ ) {
                            echo $breadcrumbs[ $i ];
                            if ( $i != count( $breadcrumbs ) - 1 ) {
                                echo $sep;
                            }
                        }
                    }
                    if ( $show_current ) {
                        echo $sep . $before . get_the_title() . $after;
                    }
                } elseif ( is_tag() ) {
                    if ( get_query_var( 'paged' ) ) {
                        $tag_id = get_queried_object_id();
                        $tag    = get_tag( $tag_id );
                        echo $sep . sprintf( $link, get_tag_link( $tag_id ), $tag->name ) . $sep . $before . sprintf( $text[ 'page' ], get_query_var( 'paged' ) ) . $after;
                    } else {
                        if ( $show_current ) {
                            echo $sep . $before . sprintf( $text[ 'tag' ], single_tag_title( '', false ) ) . $after;
                        }
                    }
                } elseif ( is_author() ) {
                    global $author;
                    $author = get_userdata( $author );
                    if ( get_query_var( 'paged' ) ) {
                        if ( $show_home_link ) {
                            echo $sep;
                        }
                        echo sprintf( $link, get_author_posts_url( $author->ID ), $author->display_name ) . $sep . $before . sprintf( $text[ 'page' ], get_query_var( 'paged' ) ) . $after;
                    } else {
                        if ( $show_home_link && $show_current ) {
                            echo $sep;
                        }
                        if ( $show_current ) {
                            echo $before . sprintf( $text[ 'author' ], $author->display_name ) . $after;
                        }
                    }
                } elseif ( is_404() ) {
                    if ( $show_home_link && $show_current ) {
                        echo $sep;
                    }
                    if ( $show_current ) {
                        echo $before . $text[ '404' ] . $after;
                    }
                } elseif ( has_post_format() && ! is_singular() ) {
                    if ( $show_home_link ) {
                        echo $sep;
                    }
                    echo get_post_format_string( get_post_format() );
                } elseif ( is_home() ) {
                    echo $before . esc_html( $this->options->get( 'blog_title' ) );
                }
                echo $wrap_after;
            }
        }

        public function the_breadcrumbs() {
            ob_start();
            $this->get_breadcrumbs();
            echo apply_filters( 'amphtml_breadcrumbs', ob_get_clean() );
        }

        public function add_auto_ads() {
            ?>
            <amp-auto-ads
                type="adsense"
                data-ad-client="<?php echo $this->adsense_auto ?>">
            </amp-auto-ads>
            <?php
        }

    }

}
