<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if ( ! class_exists( 'AMPHTML_Tab_Analytics' ) ) {

    class AMPHTML_Tab_Analytics extends AMPHTML_Tab_Abstract {

        public function get_fields() {
            return apply_filters( 'amphtml_analytics_fields', array(
                array(
                    'id'                    => 'google_analytic',
                    'title'                 => esc_html__( 'Google Analytics Code', 'amphtml' ),
                    'placeholder'           => 'UA-XXXXXXXX-Y',
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'google_analytic' ),
                    'sanitize_callback'     => array( $this, 'sanitize_google_analytic' ),
                    'description'           => esc_html__( 'Setup Google Analytics tracking ID', 'amphtml' ),
                ),
            ), $this->options );
        }

        public function display_number_field( $args ) {
            $this->display_text_field( $args, 'number' );
        }

        public function sanitize_google_analytic( $google_analytics_id ) {
            $google_analytics_id = sanitize_text_field( $google_analytics_id );
            if ( empty( $google_analytics_id ) ) {
                return '';
            }
            if ( 0 === preg_match( "/^UA-([0-9]{4,9})-([0-9]{1,4})/i", $google_analytics_id ) ) {
                add_settings_error( $this->options->get( 'google_analytic', 'name' ), 'hc_error', esc_html__( 'Insert a valid Google Analytics ID', 'amphtml' ), 'error' );
                $valid_field = $this->options->get( 'google_analytic' );
            } else {
                $valid_field = $google_analytics_id;
            }

            return $valid_field;
        }

    }

}