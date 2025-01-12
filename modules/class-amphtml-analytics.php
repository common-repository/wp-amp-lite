<?php

class AMPHTML_Analytics extends AMPHTML_Template_Abstract {

    protected $analytics_script;
    protected $available_analytics;
    protected $enabled_analytics;
    public $options;

    public function __construct() {
        $this->analytics_script    = array(
            'slug' => 'amp-analytics',
            'src'  => 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js'
        );
        $this->available_analytics = array(
            'google_analytic',   
        );
        add_action( 'amphtml_init', array( $this, 'init' ) );
        add_action( 'amphtml_before_render', array( $this, 'add_analytics_script' ) );
        add_action( 'amphtml_after_footer', array( $this, 'render_analytics' ) );
    }

    public function init() {
        $this->options = AMPHTML()->options;
        $this->check_enabled_analytics();
    }

    protected function check_enabled_analytics() {
        foreach ( $this->available_analytics as $analytic_name ) {
            if ( $analytic_val = $this->options->get( $analytic_name ) ) {
                $this->enabled_analytics[ $analytic_name ] = $analytic_val;
            }
        }
    }

    /**
     * @param $template
     */
    public function add_analytics_script( AMPHTML_Template $template ) {
        if ( ! empty( $this->enabled_analytics ) ) {
            $template->add_embedded_element( $this->analytics_script );
        }
    }

    protected function get_google_analitycs() {
        return array(
            'vars'     => array(
                'account' => $this->enabled_analytics[ 'google_analytic' ]
            ),
            'triggers' => array(
                'trackPageview' => array(
                    'on'      => 'visible',
                    'request' => 'pageview',
                ),
            ),
        );
    }

    public function render_analytics() {
        if ( is_array( $this->enabled_analytics ) && count( $this->enabled_analytics ) > 0 ) {
            foreach ( $this->enabled_analytics as $name => $val ) {
                $template = str_replace( '_', '-', $name );
                echo $this->render( $template );
            }
        }
    }

}

new AMPHTML_Analytics();
