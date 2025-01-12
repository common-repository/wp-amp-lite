<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
if ( ! class_exists( 'AMPHTML_Tab' ) ) {

    class AMPHTML_Tab {

        const DEFAULT_TAB      = 'general';
        const DEFAULT_SECTION  = 'default';
        const TAB_CLASS_PREFIX = 'AMPHTML_Tab_';

        /**
         * @var array
         */
        protected $list;
        protected $tabs = array();

        /**
         * @var AMPHTML_Options
         */
        protected $options;

        public function __construct( $options ) {
            $this->list = array(
                self::DEFAULT_TAB => esc_html__( 'General', 'amphtml' ),
                'analytics'       => esc_html__( 'Analytics', 'amphtml' ),
                'appearance'      => esc_html__( 'Appearance', 'amphtml' ),
                'templates'       => esc_html__( 'Templates', 'amphtml' ),
                'schemaorg'       => 'Schema.org',
                'troubleshooting' => esc_html__( 'Troubleshooting', 'amphtml' ),
                'status'          => esc_html__( 'System Status', 'amphtml' ),                
            );

            $this->options = $options;
        }

        public function get_list() {
            return $this->list;
        }

        public function create() {
            $this->list = apply_filters( 'amphtml_admin_tab_list', $this->list );
            foreach ( $this->list as $name => $description ) {
                $is_current      = false;
                $current_section = '';
                $className       = self::TAB_CLASS_PREFIX . ucfirst( $name );

                if ( ! class_exists( $className ) ) {
                    continue;
                }

                if ( $name == $this->get_current() ) {
                    $is_current = true;
                }

                $tab                 = new $className( $name, $this->options, $is_current );
                $this->tabs[ $name ] = $tab;
            }

            return $this;
        }

        public function get_current() {
            return $this->options->get_request_var( 'tab', AMPHTML_Tab::DEFAULT_TAB );
        }

        public function get_current_section() {
            $current_tab = $this->get( $this->get_current() );
            if ( $current_tab instanceof AMPHTML_Tab_Abstract ) {
                return $current_tab->get_current_section();
            }

            return '';
        }

        public function get( $name = '' ) {

            if ( $name && isset( $this->tabs[ $name ] ) ) {
                return $this->tabs[ $name ];
            }

            if ( count( $this->tabs ) ) {
                return $this->tabs;
            }
        }

    }

}