<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
if ( ! class_exists( 'AMPHTML_No_Conflict' ) ) {

    class AMPHTML_No_Conflict {

        public $fixed_doc_title = '';

        public function __construct() {
            $this->no_conflict();
        }

        public function no_conflict() {
            if ( AMPHTML()->is_amp() ) {
                remove_filter( 'template_redirect', 'redirect_canonical' );                
                add_filter( 'bj_lazy_load_run_filter', '__return_false' );                
                $this->hyper_cache_compatibility();
                $this->jetpack_compatibility();
                add_filter( 'amphtml_illegal_tags', array( $this, 'sanitize_goodlayer_content' ) );
                $this->disable_w3_total_cache();

                // WP Ultimate Recipe support
                if ( is_plugin_active( 'wp-ultimate-recipe/wp-ultimate-recipe.php' ) ) {
                    add_filter( 'amphtml_single_content', array( $this, 'amphtml_single_content_urecipe' ) );
                }

                if ( is_plugin_active( 'polylang/polylang.php' ) ) {
                    add_filter( 'redirect_canonical', array( $this, 'polylang_redirect_canonical' ), 10, 2 );
                }
            }
        }

        /**
         * Polylang
         */
        function polylang_redirect_canonical( $redirect_url, $requested_url ) {
            return $requested_url;
        }

        /*
         * Hyper Cache
         */

        public function hyper_cache_compatibility() {
            global $cache_stop;
            $cache_stop = true;
        }

        /*
         * Jetpack
         */

        public function jetpack_compatibility() {
            //remove sharing buttons
            add_filter( 'sharing_show', '__return_false' );
            //remove related posts
            add_filter( 'wp', array( $this, 'jetpackme_remove_rp' ), 20 );
            //remove like buttons
            add_action( 'wp', array( $this, 'jetpackme_remove_likes' ) );
            //add jetpack stats
            add_action( 'wp', array( $this, 'jetpackme_add_stats' ) );
        }

        public function jetpackme_remove_rp() {
            if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
                $jprp     = Jetpack_RelatedPosts::init();
                $callback = array( $jprp, 'filter_add_target_to_dom' );
                remove_filter( 'the_content', $callback, 40 );
            }
        }

        public function jetpackme_remove_likes() {
            remove_filter( 'the_content', 'sharing_display', 19 );
            remove_filter( 'the_excerpt', 'sharing_display', 19 );
            if ( class_exists( 'Jetpack_Likes' ) ) {
                remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
            }
        }

        public function jetpackme_add_stats() {
            if ( class_exists( 'Jetpack' ) AND method_exists( 'Jetpack', 'is_module_active' ) ) {
                if ( Jetpack::is_module_active( 'stats' ) ) {
                    if ( function_exists( 'stats_build_view_data' ) ) { //support only for latest versions
                        add_filter( 'amphtml_template_footer_content', array( $this, 'jetpackme_add_stats_pixel' ) );
                    }
                }
            }
        }

        public function jetpackme_add_stats_pixel( $footer_content ) {
            $footer_content .= '<amp-pixel src="' . $this->jetpackme_get_stats_pixel_url() . '"></amp-pixel>';

            return $footer_content;
        }

        function jetpackme_get_stats_pixel_url() {
            $data           = stats_build_view_data();
            $data[ 'host' ] = rawurlencode( $_SERVER[ 'HTTP_HOST' ] );
            $data[ 'rand' ] = 'RANDOM';
            $data[ 'ref' ]  = 'DOCUMENT_REFERRER';
            $data           = array_map( 'rawurlencode', $data );

            return add_query_arg( $data, 'https://pixel.wp.com/g.gif' );
        }

        public function sanitize_goodlayer_content( $tags ) {
            $tags[] = 'pre[class=gdlr_rp]';

            return $tags;
        }

        public function disable_w3_total_cache() {
            if ( is_plugin_active( 'w3-total-cache/w3-total-cache.php' ) ) {
                //Disables page caching for a given page.
                define( 'DONOTCACHEPAGE', true );

                //Disables database caching for given page.
                define( 'DONOTCACHEDB', true );

                //Disables minify for a given page.
                define( 'DONOTMINIFY', true );

                //Disables content delivery network for a given page.
                define( 'DONOTCDN', true );

                //Disables object cache for a given page.
                define( 'DONOTCACHEOBJECT', true );
            }
        }

        public function amphtml_single_content_urecipe( $content ) {
            if ( class_exists( 'WPURP_Recipe' ) ) {
                $recipe     = new WPURP_Recipe( get_post() );
                $recipe_box = apply_filters( 'wpurp_output_recipe', $recipe->output_string( 'amp' ), $recipe );
                if ( strpos( $content, '[recipe]' ) !== false ) {
                    $content = str_replace( '[recipe]', $recipe_box, $content );
                } else {
                    $content .= $recipe_box;
                }
                // Remove searchable part
                $content = preg_replace( "/\[wpurp-searchable-recipe\][^\[]*\[\/wpurp-searchable-recipe\]/", "", $content );
            }

            return $content;
        }


    }

}