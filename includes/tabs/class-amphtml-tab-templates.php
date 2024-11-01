<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AMPHTML_Tab_Templates' ) ) {

    class AMPHTML_Tab_Templates extends AMPHTML_Tab_Abstract {

        public function __construct( $name, $options, $is_current = false ) {
            parent::__construct( $name, $options, $is_current );
            add_filter( 'wpamp_content_tags', array( $this, 'additional_content_tags' ) );
        }

        public function get_sections() {
            return apply_filters( 'amphtml_template_sections_free', array(
                'post'    => esc_html__( 'Posts', 'amphtml' ),
                'page'    => esc_html__( 'Pages', 'amphtml' ),
                'search'  => esc_html__( 'Search', 'amphtml' ),
                'blog'    => esc_html__( 'Blog Page', 'amphtml' ),
                'archive' => esc_html__( 'Archives', 'amphtml' ),
                '404'     => esc_html__( '404 Page', 'amphtml' ),
            ) );
        }

        public function get_fields() {
            return apply_filters( 'amphtml_template_fields_free', array_merge( $this->get_404_fields( '404' ), $this->get_posts_fields( 'post' ), $this->get_page_fields( 'page' ), $this->get_search_fields( 'search' ), $this->get_blog_fields( 'blog' ), $this->get_archive_fields( 'archive' ) ), $this->options );
        }

        /*
         * 404 Page Section
         */

        public function get_404_fields( $section ) {
            $fields = array(
                array(
                    'id'                    => 'breadcrumbs_404',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'breadcrumbs_404' ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                array(
                    'id'                    => 'title_404',
                    'title'                 => esc_html__( '404 Page Title', 'amphtml' ),
                    'default'               => esc_html__( 'Oops! That page can&rsquo;t be found.', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => 'title_404' ),
                    'template_name'         => 'title_404',
                    'description'           => ''
                ),
                array(
                    'id'                    => 'content_404',
                    'title'                 => esc_html__( '404 Page Content', 'amphtml' ),
                    'default'               => esc_html__( 'Nothing was found at this location.', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_textarea_field' ),
                    'display_callback_args' => array( 'id' => 'content_404' ),
                    'sanitize_callback'     => array( $this, 'sanitize_textarea_content' ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Plain html without inline styles allowed. ' . '(<a href="https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md#html5-tag-whitelist" target="_blank">HTML5 Tag Whitelist</a>)', 'amphtml' )
                ),
                array(
                    'id'                    => 'search_form_404',
                    'title'                 => esc_html__( 'Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => 'search_form_404' ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
            );

            return apply_filters( 'amphtml_template_404_fields', $fields, $section, $this );
        }

        /**
         * Add additional allowed tags for custom content here
         *
         * @param array $tags
         *
         * @return array of tags
         */
        public function additional_content_tags( $tags ) {
            $tags[ 'amp-ad' ] = array(
                'src'            => true,
                'type'           => true,
                'width'          => true,
                'height'         => true,
                'data-slot'      => true,
                'data-ad-client' => true,
                'data-ad-slot'   => true
            );

            $tags[ 'amp-img' ] = array(
                'src'    => true,
                'srcset' => true,
                'alt'    => true,
                'height' => true,
                'width'  => true,
                'class'  => true,
                'id'     => true,
                'layout' => true,
                'title'  => true
            );

            $tags[ 'form' ] = array(
                'action'         => true,
                'action-xhr'     => true,
                'method'         => true,
                'target'         => true,
                'autocomplete'   => true,
                'name'           => true,
                'enctype'        => true,
                'accept-charset' => true
            );

            $tags[ 'input' ] = array(
                'type'  => true,
                'name'  => true,
                'value' => true
            );

            return $tags;
        }

        public function sanitize_textarea_content( $textarea_content ) {
            $tags                           = wp_kses_allowed_html( 'post' );
            $tags[ 'form' ][ 'action-xhr' ] = true;

            $not_allowed = array(
                'font',
                'menu',
                'nav'
            );

            foreach ( $tags as $key => $attr ) {
                if ( in_array( $key, $not_allowed ) ) {
                    unset( $tags[ $key ] );
                }
            }

            $tags = apply_filters( 'wpamp_content_tags', $tags );

            return wp_kses( $textarea_content, $tags );
        }

        /*
         * Posts Section
         */

        public function get_posts_fields( $section ) {

            $top_ad_block    = array();
            $bottom_ad_block = array();

            $fields = array(
                array(
                    'id'                    => $section . '_breadcrumbs',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_breadcrumbs', 'section' => $section ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                // Search form
                array(
                    'id'                    => $section . '_search_form',
                    'title'                 => esc_html__( 'Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_search_form', 'section' => $section ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_original_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'View Original Version' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_original_btn_text', 'section' => $section ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                // Block commnets button
                array(
                    'id'                    => $section . '_comments_btn_block',
                    'title'                 => esc_html__( 'Comments Button', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_comments_btn_block' ),
                    'display_callback_args' => array( 'id' => $section . '_comments_btn_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_comments_btn_block' ),
                    'template_name'         => 'comments_btn_block',
                    'description'           => esc_html__( 'Show link to the comment form', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_comments_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'Comments' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_comments_btn_text', 'section' => $section ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                // Post title
                array(
                    'id'                    => $section . '_title',
                    'title'                 => esc_html__( 'Post Title', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_title', 'section' => $section ),
                    'template_name'         => 'title',
                    'description'           => 'Show post title',
                ),
                array(
                    'id'                    => $section . '_featured_image',
                    'title'                 => esc_html__( 'Featured Image', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image', 'section' => $section ),
                    'template_name'         => 'featured_image',
                    'description'           => esc_html__( 'Show post thumbnail', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_meta',
                    'title'                 => esc_html__( 'Post Meta Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_meta', 'section' => $section ),
                    'template_name'         => 'post_meta',
                    'description'           => esc_html__( 'Show post author, categories and published time', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_content',
                    'title'                 => esc_html__( 'Post Content', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_content', 'disabled' => true, 'checked' => true, 'section' => $section ),
                    'template_name'         => 'post_content',
                    'description'           => 'Show post content',
                ),
                array(
                    'id'                    => $section . '_social_share',
                    'title'                 => esc_html__( 'Social Share Buttons', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_social_share', 'section' => $section ),
                    'template_name'         => 'social-share',
                    'description'           => esc_html__( 'Show social share buttons', 'amphtml' ),
                ),
                // Related posts block
                array(
                    'id'                    => $section . '_related_content_block',
                    'title'                 => esc_html__( 'Related Posts', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_related_posts_block' ),
                    'display_callback_args' => array( 'id' => $section . '_related_content_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_related_posts_content_block' ),
                    'template_name'         => 'related-posts',
                    'description'           => esc_html__( 'Show related posts', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_related_title',
                    'title'                 => esc_html__( 'Related Posts Title', 'amphtml' ),
                    'default'               => esc_html__( 'You May Also Like' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_related_title', 'section' => $section ),
                    'description'           => esc_html__( 'Title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_related_count',
                    'title'                 => esc_html__( 'Number of Related Posts', 'amphtml' ),
                    'default'               => 3,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_related_count', 'section' => $section ),
                    'description'           => esc_html__( 'Post count', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_related_thumbnail',
                    'title'                 => esc_html__( 'Post', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_related_thumbnail', 'section' => $section ),
                    'description'           => esc_html__( 'Show Post Thumbnail', 'amphtml' ),
                ),
                // Recent posts block
                array(
                    'id'                    => $section . '_recent_content_block',
                    'title'                 => esc_html__( 'Recent Posts', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_recent_posts_block' ),
                    'display_callback_args' => array( 'id' => $section . '_recent_content_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_recent_posts_content_block' ),
                    'template_name'         => 'recent-posts',
                    'description'           => esc_html__( 'Show recent posts', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_recent_title',
                    'title'                 => esc_html__( 'Recent Posts Title', 'amphtml' ),
                    'default'               => esc_html__( 'Recent Posts' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_recent_title', 'section' => $section ),
                    'description'           => esc_html__( 'Title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_recent_count',
                    'title'                 => esc_html__( 'Number of Recent Posts', 'amphtml' ),
                    'default'               => 3,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_recent_count', 'section' => $section ),
                    'description'           => esc_html__( 'Post count', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_recent_thumbnail',
                    'title'                 => esc_html__( 'Post', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_recent_thumbnail', 'section' => $section ),
                    'description'           => esc_html__( 'Show Post Thumbnail', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_comments',
                    'title'                 => esc_html__( 'Post Comments', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_comments', 'section' => $section ),
                    'template_name'         => 'view_comments',
                    'description'           => esc_html__( 'Show post comments', 'amphtml' ),
                ),
            );

            return apply_filters( 'amphtml_template_post_fields', $fields, $section, $this );
        }

        /*
         * Pages Section
         */

        public function get_page_fields( $section ) {
            $top_ad_block    = array();
            $bottom_ad_block = array();
            $socail_share    = array();

            $fields = array(
                array(
                    'id'                    => $section . '_breadcrumbs',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_breadcrumbs', 'section' => $section ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_original_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'View Original Version' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_original_btn_text', 'section' => $section ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                // Search form
                array(
                    'id'                    => $section . '_search_form',
                    'title'                 => esc_html__( 'Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_search_form', 'section' => $section ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_title',
                    'title'                 => esc_html__( 'Page Title', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_title', 'section' => $section ),
                    'template_name'         => 'title',
                    'description'           => 'Show page title',
                ),
                array(
                    'id'                    => $section . '_featured_image',
                    'title'                 => esc_html__( 'Featured Image', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image', 'section' => $section ),
                    'template_name'         => 'featured_image',
                    'description'           => esc_html__( 'Show page thumbnail', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_content',
                    'title'                 => esc_html__( 'Page Content', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_content', 'disabled' => true, 'checked' => true, 'section' => $section ),
                    'description'           => 'Show page content',
                    'template_name'         => 'post_content'
                ),
                array(
                    'id'                    => $section . '_social_share',
                    'title'                 => esc_html__( 'Social Share Buttons', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_social_share', 'section' => $section ),
                    'template_name'         => 'social-share',
                    'description'           => esc_html__( 'Show social share buttons', 'amphtml' ),
                )
            );

            return apply_filters( 'amphtml_template_page_fields', $fields, $section, $this );
        }

        /*
         * Search Page Section
         */

        public function get_search_fields( $section ) {
            $top_ad_block    = array();
            $bottom_ad_block = array();

            $fields = array(
                array(
                    'id'                    => $section . '_breadcrumbs',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_breadcrumbs', 'section' => $section ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_original_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'View Original Version' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_original_btn_text', 'section' => $section ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_title',
                    'title'                 => esc_html__( 'Page Title', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_title', 'section' => $section ),
                    'template_name'         => 'search_title',
                    'description'           => esc_html__( 'Show search title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_content_block',
                    'title'                 => esc_html__( 'Content Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_archive_content_block' ),
                    'display_callback_args' => array( 'id' => $section . '_content_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_archive_content_block' ),
                    'template_name'         => 'archive_content_block',
                    'description'           => esc_html__( 'Search Page Content', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_featured_image',
                    'title'                 => esc_html__( 'Featured Image', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image', 'section' => $section ),
                    'description'           => esc_html__( 'Show posts thumbnail', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_featured_image_link',
                    'title'                 => esc_html__( 'Featured Images Link', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image_link', 'section' => $section ),
                    'description'           => esc_html__( 'Link featured images to the post', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_meta',
                    'title'                 => esc_html__( 'Post Meta Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_meta', 'section' => $section ),
                    'description'           => esc_html__( 'Show posts author, categories and published time', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_excerpt',
                    'title'                 => esc_html__( 'Excerpt Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_excerpt', 'section' => $section ),
                    'description'           => esc_html__( 'Show excerpt', 'amphtml' ),
                ),
                // Search form
                array(
                    'id'                    => $section . '_search_form',
                    'title'                 => esc_html__( 'Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_search_form', 'section' => $section ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
            );

            return apply_filters( 'amphtml_template_search_fields', $fields, $section, $this );
        }

        /*
         * Blog Page Section
         */

        public function get_blog_fields( $section ) {
            $top_ad_block    = array();
            $bottom_ad_block = array();

            $fields = array(
                array(
                    'id'                    => $section . '_breadcrumbs',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_breadcrumbs', 'section' => $section ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_original_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'View Original Version' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_original_btn_text', 'section' => $section ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_title',
                    'title'                 => esc_html__( 'Blog Page Title', 'amphtml' ),
                    'default'               => esc_html__( 'Blog', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => $section . '_title', 'section' => $section ),
                    'template_name'         => $section . '_title',
                    'description'           => ''
                ),
                array(
                    'id'                    => $section . '_content_block',
                    'title'                 => esc_html__( 'Content Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_archive_content_block' ),
                    'display_callback_args' => array( 'id' => $section . '_content_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_archive_content_block' ),
                    'template_name'         => 'archive_content_block',
                    'description'           => esc_html__( 'Blog Page Content', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_featured_image',
                    'title'                 => esc_html__( 'Featured Image', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image', 'section' => $section ),
                    'description'           => esc_html__( 'Show posts thumbnail', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_featured_image_link',
                    'title'                 => esc_html__( 'Featured Images Link', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image_link', 'section' => $section ),
                    'description'           => esc_html__( 'Link featured images to the post', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_meta',
                    'title'                 => esc_html__( 'Post Meta Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_meta', 'section' => $section ),
                    'description'           => esc_html__( 'Show posts author, categories and published time', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_excerpt',
                    'title'                 => esc_html__( 'Excerpt Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_excerpt', 'section' => $section ),
                    'description'           => esc_html__( 'Show excerpt', 'amphtml' ),
                ),
                // Search form
                array(
                    'id'                    => $section . '_search_form',
                    'title'                 => esc_html__( 'Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_search_form', 'section' => $section ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
            );

            return apply_filters( 'amphtml_template_blog_fields', $fields, $section, $this );
        }

        /*
         * Archive Page Section
         */

        public function get_archive_fields( $section ) {
            $top_ad_block    = array();
            $bottom_ad_block = array();

            $fields = array(
                array(
                    'id'                    => $section . '_breadcrumbs',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_breadcrumbs', 'section' => $section ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_original_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'View Original Version' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_original_btn_text', 'section' => $section ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_title',
                    'title'                 => esc_html__( 'Archive Title', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_title', 'disabled' => true, 'checked' => true, 'section' => $section ),
                    'description'           => 'Show archive title',
                ),
                array(
                    'id'                    => $section . '_desc',
                    'title'                 => esc_html__( 'Description', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_desc', 'section' => $section ),
                    'description'           => esc_html__( 'Show description of archive page', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_content_block',
                    'title'                 => esc_html__( 'Content Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_archive_content_block' ),
                    'display_callback_args' => array( 'id' => $section . '_content_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_archive_content_block' ),
                    'template_name'         => 'archive_content_block',
                    'description'           => '',
                ),
                array(
                    'id'                    => $section . '_featured_image',
                    'title'                 => esc_html__( 'Featured Images', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image', 'section' => $section ),
                    'description'           => esc_html__( 'Show posts thumbnails', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_featured_image_link',
                    'title'                 => esc_html__( 'Featured Images Link', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_featured_image_link', 'section' => $section ),
                    'description'           => esc_html__( 'Link featured images to the post', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_meta',
                    'title'                 => esc_html__( 'Post Meta Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_meta', 'section' => $section ),
                    'description'           => esc_html__( 'Show post author, categories and published time', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_excerpt',
                    'title'                 => esc_html__( 'Post Excerpt', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_excerpt', 'section' => $section ),
                    'description'           => esc_html__( 'Show post excerpt', 'amphtml' ),
                ),
                // Search form
                array(
                    'id'                    => $section . '_search_form',
                    'title'                 => esc_html__( 'Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_search_form', 'section' => $section ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
            );

            return apply_filters( 'amphtml_template_archive_fields', $fields, $section, $this );
        }

        public function get_section_fields( $id ) {
            $fields_order = get_option( self::ORDER_OPT );
            $fields_order = maybe_unserialize( $fields_order );
            $fields_order = isset( $fields_order[ $id ] ) ? maybe_unserialize( $fields_order[ $id ] ) : array();
            if ( ! count( $fields_order ) ) {
                return parent::get_section_fields( $id );
            }
            $fields = array();
            foreach ( $fields_order as $field_name ) {
                $fields[] = $this->search_field_id( $field_name );
            }

            return array_merge( $fields, parent::get_section_fields( $id ) );
        }

        public function display_archive_content_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_featured_image' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_featured_image_link' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_meta' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_excerpt' ) ); ?>
            </fieldset>
            <?php
        }

        public function display_comments_btn_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_comments_btn_block' ) ); ?>
                <br>
                <?php $this->display_text_field( array( 'id' => $section . '_comments_btn_text' ) ); ?>
            </fieldset>
            <?php
        }

        public function display_related_posts_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_related_content_block' ) ); ?>
                <br>
                <?php $this->display_text_field( array( 'id' => $section . '_related_title' ) ); ?>
                <br>
                <?php $this->display_text_field( array( 'id' => $section . '_related_count' ), 'number' ); ?>
                <br>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_related_thumbnail' ) ); ?>
            </fieldset>
            <?php
        }

        public function display_recent_posts_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_recent_content_block' ) ); ?>
                <br>
                <?php $this->display_text_field( array( 'id' => $section . '_recent_title' ) ); ?>
                <br>
                <?php $this->display_text_field( array( 'id' => $section . '_recent_count' ), 'number' ); ?>
                <br>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_recent_thumbnail' ) ); ?>
            </fieldset>
            <?php
        }

        public function sanitize_archive_content_block() {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_featured_image',
                $section . '_featured_image_link',
                $section . '_meta',
                $section . '_excerpt',
            ) );

            return 1;
        }

        public function sanitize_comments_btn_block() {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_comments_btn_text',
            ) );

            $block_name = $this->options->get( $section . '_comments_btn_block', 'name' );

            return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
        }

        public function sanitize_related_posts_content_block( $args ) {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_related_title',
                $section . '_related_count',
                $section . '_related_thumbnail'
            ) );

            $block_name = $this->options->get( $section . '_related_content_block', 'name' );

            return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
        }

        public function sanitize_recent_posts_content_block( $args ) {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_recent_title',
                $section . '_recent_count',
                $section . '_recent_thumbnail'
            ) );

            $block_name = $this->options->get( $section . '_recent_content_block', 'name' );

            return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
        }

        public function get_section_callback( $id ) {
            return array( $this, 'section_callback' );
        }

        public function section_callback( $page, $section ) {
            global $wp_settings_fields;

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

                echo "<tr data-name='{$field[ 'id' ]}' id='pos_{$row_id}' {$class}>";
                echo '<th class="drag"></th>';
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