<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Public function for sanitizing content
 */
if ( function_exists( 'AMPHTML' ) ) {

    function esc_amphtml( $content ) {
        $amphtml = AMPHTML()->get_template()->get_sanitize_obj()->sanitize_content( $content );

        return apply_filters( 'esc_amphtml', $amphtml );
    }

}

/**
 * is_post_type_viewable() for older WordPress versions
 */
if ( ! function_exists( 'is_post_type_viewable' ) ) {

    function is_post_type_viewable( $post_type ) {
        if ( is_scalar( $post_type ) ) {
            $post_type = get_post_type_object( $post_type );
            if ( ! $post_type ) {
                return false;
            }
        }

        return $post_type->publicly_queryable || ( $post_type->_builtin && $post_type->public );
    }

}

/**
 * Check if AMP page loaded
 * @return bool
 */
function is_wp_amp() {
    $endpoint_opt = get_option( 'amphtml_endpoint' );
    $endpoint     = ( $endpoint_opt ) ? $endpoint_opt : AMPHTML::AMP_QUERY;

    if ( '' == get_option( 'permalink_structure' ) ) {
        parse_str( $_SERVER[ 'QUERY_STRING' ], $url );

        return isset( $url[ $endpoint ] );
    }

    $url_parts   = explode( '?', $_SERVER[ "REQUEST_URI" ] );
    $query_parts = explode( '/', $url_parts[ 0 ] );

    $is_amp = ( in_array( $endpoint, $query_parts ) );

    return $is_amp;
}

function amphtml_get_default_post_types( $args = '' ) {
    $types        = array();
    $default_args = array(
        'public' => true
    );
    $args         = is_array( $args ) ? $args : $default_args;
    $post_types   = get_post_types( $args, 'object' );
    foreach ( $post_types as $type ) {
        $types[] = $type->name;
    }

    return $types;
}
