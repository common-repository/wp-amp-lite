<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

include_once( ABSPATH . 'wp-admin/includes/media.php' );
if ( ! class_exists( 'AMPHTML_Template_Abstract' ) ) {

    class AMPHTML_Template_Abstract {

        const TEMPLATE_DIR                    = 'templates';
        const TEMPLATE_PART_DIR               = 'templates/parts';
        const TEMPLATE_CART_DIR               = 'templates/add-to-cart';        
        const STYLE_DIR                       = 'css';
        const SITE_ICON_SIZE                  = 32;
        const SCHEMA_IMG_MIN_WIDTH            = 696;

        public $properties;
        protected $sanitizer;
        protected $embedded_elements = array();
        protected $template          = 'base';
        public $template_content     = '';
        protected $fonts;
        protected $blocks;

        /**
         * @var AMPHTML_Options
         */
        public $options;

        public function __get( $key ) {
            if ( isset( $this->properties[ $key ] ) ) {
                return $this->properties[ $key ];
            }

            return '';
        }

        public function __set( $key, $value ) {
            $this->properties[ $key ] = $value;
        }

        public function get_embedded_elements() {
            return apply_filters( 'amphtml_embedded_elements', $this->embedded_elements );
        }

        public function add_embedded_element( $new_element ) {
            $slugs = array();

            foreach ( $this->embedded_elements as $element ) {
                $slugs[] = $element[ 'slug' ];
            }

            if ( ! in_array( $new_element[ 'slug' ], $slugs ) ) {
                $this->embedded_elements[] = $new_element;
            }

            return $this;
        }

        public function render( $filename = '' ) {

            if ( ! $filename ) {
                $filename = $this->template;
            }

            $template_path = $this->get_template_path( $filename );

            if ( file_exists( $template_path ) ) {
                ob_start();
                include( $template_path );

                return ob_get_clean();
            }
        }

        public function get_template_path( $filename ) {
            //theme templates            
            
            $path = apply_filters( 'amphtml_template_path_free', array(), $filename );            
            
            $path[] = locate_template( array(
                AMPHTML()->get_plugin_folder_name() . DIRECTORY_SEPARATOR . $filename . '.php'
            ), false );
            $path[] = locate_template( array(
                AMPHTML()->get_plugin_folder_name() . DIRECTORY_SEPARATOR . 'parts' . DIRECTORY_SEPARATOR . $filename . '.php'
            ), false );
            $path[] = locate_template( array(
                AMPHTML()->get_plugin_folder_name() . DIRECTORY_SEPARATOR . 'add-to-cart' . DIRECTORY_SEPARATOR . $filename . '.php'
            ), false );
            //plugin templates
            
            $path[] = $this->get_dir_path( self::TEMPLATE_DIR ) . DIRECTORY_SEPARATOR . $filename . '.php';
            $path[] = $this->get_dir_path( self::TEMPLATE_PART_DIR ) . DIRECTORY_SEPARATOR . $filename . '.php';
            $path[] = $this->get_dir_path( self::TEMPLATE_CART_DIR ) . DIRECTORY_SEPARATOR . $filename . '.php';

            foreach ( $path as $template ) {
                if ( file_exists( $template ) ) {
                    return $template;
                }
            }
        }

        public function get_option( $option ) {
            return $this->options->get( $option );
        }

        protected function get_dir_path( $sub_dir ) {
            $amphtml_dir = AMPHTML()->get_amphtml_path();

            if ( is_dir( $amphtml_dir . $sub_dir ) ) {
                return $amphtml_dir . $sub_dir;
            }

            return false;
        }

    }

}