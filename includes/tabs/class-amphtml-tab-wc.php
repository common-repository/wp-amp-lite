<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if ( ! class_exists( 'AMPHTML_Tab_Wc' ) ) {

    class AMPHTML_Tab_Wc extends AMPHTML_Tab_Abstract {

        public function get_sections() {
            return array(
                'shop'        => esc_html__( 'Shop Page', 'amphtml' ),
                'wc_archives' => esc_html__( 'Product Archives', 'amphtml' ),
                'add_to_cart' => esc_html__( 'Add to Cart', 'amphtml' ),
            );
        }

        public function get_fields() {
            return array_merge( $this->get_add_to_cart_fields( 'add_to_cart' ), $this->get_shop_fields( 'shop' ), $this->get_archives_fields( 'wc_archives' ) );
        }

        public function get_shop_fields( $section ) {
            return array(
                array(
                    'id'                    => $section . '_view',
                    'title'                 => esc_html__( 'View', 'amphtml' ),
                    'default'               => 'list',
                    'display_callback'      => array( $this, 'display_select' ),
                    'display_callback_args' => array(
                        'id'             => $section . '_view',
                        'class'          => 'unsortable',
                        'select_options' => array(
                            'list'   => 'List',
                            'list_2' => 'List 2',
                            'grid'   => 'Grid'
                        ),
                        'section'        => $section
                    ),
                    'section'               => $section
                ),
                array(
                    'id'                    => $section . '_breadcrumbs',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_breadcrumbs',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_original_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'View Original Version' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_original_btn_text' ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_search_form',
                    'title'                 => esc_html__( 'Product Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_search_form',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_sort',
                    'title'                 => esc_html__( 'Sorting Block', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_sort' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_sort_block',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'sanitize_callback'     => array( $this, 'sanitize_sort' ),
                    'template_name'         => 'sort_block',
                    'description'           => '',
                ),
                array(
                    'id'                    => $section . '_sort_block',
                    'title'                 => esc_html__( 'Sort', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_sort_block', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sorting Block"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_popularity',
                    'title'                 => esc_html__( 'Popularity', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_popularity', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by popularity"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_average_rating',
                    'title'                 => esc_html__( 'Average rating', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_average_rating', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by average rating"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_date',
                    'title'                 => esc_html__( 'Date', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_date', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by latest"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_price_asc',
                    'title'                 => esc_html__( 'Price', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_price_asc', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by price: low to high"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_price_desc',
                    'title'                 => esc_html__( 'Price Desc', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_price_desc', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by price: high to low"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_link_image',
                    'title'                 => esc_html__( 'Link Image', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_link_image',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'description'           => esc_html__( 'Link product images', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_image',
                    'title'                 => esc_html__( 'Image', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_image', 'section' => $section ),
                    'description'           => esc_html__( 'Show product images', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_rating',
                    'title'                 => esc_html__( 'Rating', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_rating', 'section' => $section ),
                    'description'           => esc_html__( 'Show product rating', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_short_desc',
                    'title'                 => esc_html__( 'Short Description', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_short_desc', 'section' => $section ),
                    'template_name'         => 'wc_archives_short_desc',
                    'description'           => esc_html__( 'Show product short descriptions', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_add_to_cart_block',
                    'title'                 => esc_html__( 'Add To Cart Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_shop_add_to_cart_block' ),
                    'display_callback_args' => array( 'id' => $section . '_add_to_cart_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_shop_add_to_cart_block' ),
                    'template_name'         => 'add_to_cart_block',
                    'description'           => '',
                ),
                array(
                    'id'                    => $section . '_price',
                    'title'                 => esc_html__( 'Price', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_price', 'section' => $section ),
                    'description'           => esc_html__( 'Show product prices', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_add_to_cart',
                    'title'                 => esc_html__( 'Add to Cart', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_add_to_cart', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Add to Cart" button', 'amphtml' ),
                ),
            );
        }

        public function get_archives_fields( $section ) {
            return array(
                array(
                    'id'                    => $section . '_view',
                    'title'                 => esc_html__( 'View', 'amphtml' ),
                    'default'               => 'list',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_select' ),
                    'display_callback_args' => array(
                        'id'             => $section . '_view',
                        'class'          => 'unsortable',
                        'select_options' => array(
                            'list'   => 'List',
                            'list_2' => 'List 2',
                            'grid'   => 'Grid'
                        ),
                        'section'        => $section,
                    ),
                ),
                array(
                    'id'                    => $section . '_breadcrumbs',
                    'title'                 => esc_html__( 'Breadcrumbs', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_breadcrumbs',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'template_name'         => 'breadcrumb',
                    'description'           => esc_html__( 'Show breadcrumbs', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_search_form',
                    'title'                 => esc_html__( 'Product Search Form', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_search_form',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'template_name'         => 'searchform',
                    'description'           => esc_html__( 'Enable search form. Needs SSL certificate for AMP validation.', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_desc',
                    'title'                 => esc_html__( 'Description', 'amphtml' ),
                    'default'               => 1,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_desc',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'section'               => $section,
                    'description'           => esc_html__( 'Show description of archive page', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_original_btn_text',
                    'title'                 => '',
                    'default'               => esc_html__( 'View Original Version' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_original_btn_text' ),
                    'description'           => esc_html__( 'Button title', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_sort',
                    'title'                 => esc_html__( 'Sorting Block', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_sort' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_sort_block',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'sanitize_callback'     => array( $this, 'sanitize_sort' ),
                    'template_name'         => 'sort_block',
                    'description'           => '',
                ),
                array(
                    'id'                    => $section . '_sort_block',
                    'title'                 => esc_html__( 'Sort', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_sort_block', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sorting Block"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_popularity',
                    'title'                 => esc_html__( 'Popularity', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_popularity', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by popularity"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_average_rating',
                    'title'                 => esc_html__( 'Average rating', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_average_rating', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by average rating"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_date',
                    'title'                 => esc_html__( 'Date', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_date', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by latest"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_price_asc',
                    'title'                 => esc_html__( 'Price', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_price_asc', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by price: low to high"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_price_desc',
                    'title'                 => esc_html__( 'Price Desc', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_price_desc', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Sort by price: high to low"', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_link_image',
                    'title'                 => esc_html__( 'Link Image', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array(
                        'id'      => $section . '_link_image',
                        'class'   => 'unsortable',
                        'section' => $section
                    ),
                    'description'           => esc_html__( 'Link product images', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_image',
                    'title'                 => esc_html__( 'Image', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_image', 'section' => $section ),
                    'description'           => esc_html__( 'Show product images', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_rating',
                    'title'                 => esc_html__( 'Rating', 'amphtml' ),
                    'default'               => 0,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_rating', 'section' => $section ),
                    'description'           => esc_html__( 'Show product rating', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_short_desc',
                    'title'                 => esc_html__( 'Short Description', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_checkbox_field' ),
                    'display_callback_args' => array( 'id' => $section . '_short_desc', 'section' => $section ),
                    'description'           => esc_html__( 'Show product short descriptions', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_add_to_cart_block',
                    'title'                 => esc_html__( 'Add To Cart Block', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_shop_add_to_cart_block' ),
                    'display_callback_args' => array( 'id' => $section . '_add_to_cart_block', 'section' => $section ),
                    'sanitize_callback'     => array( $this, 'sanitize_shop_add_to_cart_block' ),
                    'template_name'         => 'add_to_cart_block',
                    'description'           => '',
                ),
                array(
                    'id'                    => $section . '_price',
                    'title'                 => esc_html__( 'Price', 'amphtml' ),
                    'default'               => 1,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_price', 'section' => $section ),
                    'section'               => $section,
                    'description'           => esc_html__( 'Show product prices', 'amphtml' ),
                ),
                array(
                    'id'                    => $section . '_add_to_cart',
                    'title'                 => esc_html__( 'Add to Cart', 'amphtml' ),
                    'default'               => 1,
                    'section'               => $section,
                    'display_callback'      => array( $this, '' ),
                    'display_callback_args' => array( 'id' => $section . '_add_to_cart', 'section' => $section ),
                    'description'           => esc_html__( 'Show "Add to Cart" button', 'amphtml' ),
                ),
            );
        }

        public function get_add_to_cart_fields( $section ) {
            return array(
                array(
                    'id'                    => $section . '_text',
                    'title'                 => esc_html__( 'Add to Cart Text', 'amphtml' ),
                    'default'               => esc_html__( 'Add To Cart', 'amphtml' ),
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_text_field' ),
                    'display_callback_args' => array( 'id' => $section . '_text', 'section' => $section ),
                    'description'           => esc_html__( '"Add to Cart" button text', 'amphtml' )
                ),
                array(
                    'id'                    => $section . '_behav',
                    'title'                 => esc_html__( 'Add to Cart Behavior', 'amphtml' ),
                    'default'               => $section . '_ajax',
                    'section'               => $section,
                    'display_callback'      => array( $this, 'display_add_to_cart_behav' ),
                    'display_callback_args' => array( 'id' => $section . '_behav', 'section' => $section ),
                    'description'           => esc_html__( '"Add to Cart" button click action', 'amphtml' ),
                ),
            );
        }

        /*
         * Add To Cart Section
         */

        public function display_add_to_cart_behav( $args ) {
            $section = $args[ 'section' ];
            ?>
            <select style="width: 28%" id="add_to_cart_behav"
                    name="<?php echo $this->options->get( $section . '_behav', 'name' ) ?>">
                <option value="add_to_cart_ajax" <?php selected( $this->options->get( $section . '_behav' ), 'add_to_cart_ajax' ) ?>>
                    <?php esc_html_e( 'Enable AJAX add to cart buttons', 'amphtml' ); ?>
                </option>
                <option value="add_to_cart" <?php selected( $this->options->get( $section . '_behav' ), 'add_to_cart' ) ?>>
                    <?php esc_html_e( 'Add to cart and redirect to product page', 'amphtml' ); ?>
                </option>
                <option
                    value="add_to_cart_cart" <?php selected( $this->options->get( $section . '_behav' ), 'add_to_cart_cart' ) ?>>
                        <?php esc_html_e( 'Add to cart and redirect to cart page', 'amphtml' ); ?>
                </option>
                <option
                    value="add_to_cart_checkout" <?php selected( $this->options->get( $section . '_behav' ), 'add_to_cart_checkout' ) ?>>
                        <?php esc_html_e( 'Add to cart and redirect to checkout page', 'amphtml' ); ?>
                </option>
                <option value="redirect" <?php selected( $this->options->get( $section . '_behav' ), 'redirect' ) ?>>
                    <?php esc_html_e( 'Redirect to product page', 'amphtml' ); ?>
                </option>
            </select>
            <p class="description"><?php esc_html_e( $this->options->get( $section . '_behav', 'description' ), 'amphtml' ) ?></p>
            <?php
        }

        public function display_add_to_cart_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_price' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_stock_status' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_qty' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_options' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_add_to' ) ); ?>
            </fieldset>
            <?php
        }

        public function display_description_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_desc' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_attributes' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_reviews' ) ); ?>

            </fieldset>
            <?php
        }

        public function display_related_products_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php
                $this->display_checkbox_field( array( 'id' => $section . '_related_products_block' ) );
                if ( $this->options->get( $section . '_related_products_block' ) ) {
                    $this->display_checkbox_field( array( 'id' => $section . '_related_rating' ) );
                    $this->display_checkbox_field( array( 'id' => $section . '_related_price' ) );
                }
                ?>
            </fieldset>
            <?php
        }

        public function display_shop_add_to_cart_block( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_price' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_add_to_cart' ) ); ?>
            </fieldset>
            <?php
        }

        public function display_sort( $args ) {
            $section = $args[ 'section' ];
            ?>
            <fieldset>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_sort_block' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_popularity' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_average_rating' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_date' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_price_asc' ) ); ?>
                <?php $this->display_checkbox_field( array( 'id' => $section . '_price_desc' ) ); ?>            
            </fieldset>
            <?php
        }

        public function sanitize_add_to_cart_block() {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_price',
                $section . '_stock_status',
                $section . '_qty',
                $section . '_options',
                $section . '_add_to'
            ) );

            return 1;
        }

        public function sanitize_description_block() {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_desc',
                $section . '_attributes',
                $section . '_reviews'
            ) );

            return 1;
        }

        public function sanitize_related_products_block() {
            $section    = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_related_rating',
                $section . '_related_price',
            ) );
            $block_name = $this->options->get( $section . '_related_products_block', 'name' );

            return isset( $_POST[ $block_name ] ) ? sanitize_text_field( $_POST[ $block_name ] ) : '';
        }

        public function sanitize_shop_add_to_cart_block() {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_price',
                $section . '_add_to_cart'
            ) );

            return 1;
        }

        public function sanitize_sort() {
            $section = sanitize_text_field( $_POST[ 'section' ] );
            $this->update_fieldset( array(
                $section . '_sort_block',
                $section . '_popularity',
                $section . '_average_rating',
                $section . '_date',
                $section . '_price_asc',
                $section . '_price_desc',
            ) );

            return 1;
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

            $fields = array_merge( $fields, parent::get_section_fields( $id ) );

            // Move view option to top of list
            foreach ( array_reverse( $fields ) as $inx => $field ) {
                if ( isset( $field[ 'display_callback_args' ][ 'class' ] ) && $field[ 'display_callback_args' ][ 'class' ] == 'unsortable' ) {
                    array_unshift( $fields, $field );
                }
            }

            return $fields;
        }

        public function get_section_callback( $id ) {
            switch ( $id ) {
                case 'product':
                case 'shop':
                case 'wc_archives':
                    return array( $this, 'product_section_callback' );
                default:
                    return parent::get_section_callback( $id );
            }
        }

        public function product_section_callback( $page, $section ) {
            global $wp_settings_fields;

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