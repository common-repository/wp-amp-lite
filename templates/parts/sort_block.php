<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/parts/shop_sort_block.php.
 *
 * @var $this AMPHTML_WC
 */
global $wp;
$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
$section = $this->get_section();

$catalog_orderby_options = array(
    'menu_order' => esc_html__( 'Default sorting', 'woocommerce' ),
);
if ( $this->options->get( $section . '_popularity' ) ) {
    $catalog_orderby_options[ 'popularity' ] = esc_html__( 'Sort by popularity', 'woocommerce' );
}
if ( $this->options->get( $section . '_average_rating' ) ) {
    $catalog_orderby_options[ 'rating' ] = esc_html__( 'Sort by average rating', 'woocommerce' );
}
if ( $this->options->get( $section . '_date' ) ) {
    $catalog_orderby_options[ 'date' ] = esc_html__( 'Sort by latest', 'woocommerce' );
}
if ( $this->options->get( $section . '_price_asc' ) ) {
    $catalog_orderby_options[ 'price' ] = esc_html__( 'Sort by price: low to high', 'woocommerce' );
}
if ( $this->options->get( $section . '_price_desc' ) ) {
    $catalog_orderby_options[ 'price-desc' ] = esc_html__( 'Sort by price: high to low', 'woocommerce' );
}
?>

<form class="woocommerce-ordering" action="<?php echo $current_url; ?>" target="_top" method="get">
    <select name="orderby" class="orderby">
        <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
            <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="hidden" name="paged" value="1" />
    <input class="i-button" type="submit" value="<?php esc_html_e( 'Sort', 'amphtml' ); ?>"/>
    <?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>

