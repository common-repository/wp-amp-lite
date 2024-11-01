<?php
/**
 * The Template for render AMP HTML WooCommerce  loop content for archive pages
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/wc-content-product-list-2.php.
 *
 * @var $this AMPHTML_Template
 */
$post_link = $this->get_amphtml_link( get_permalink() );
$section = $this->get_section();
?>
<div class="amphtml-content product-card">
    <div class="product-card-left">
        <?php echo $this->render( $section . '_image' ); ?>
        <?php echo $this->render( $section . '_rating' ); ?>
    </div>
    <div class="product-card-right">
        <h2 class="amphtml-title">
            <a href="<?php echo $post_link; ?>"
               title="<?php echo wp_kses_data( $this->title ); ?>">
                   <?php echo wp_kses_data( $this->title ); ?>
            </a>
        </h2>

        <?php echo $this->render( $section . '_short_desc' ); ?>
        <?php echo $this->render( 'add_to_cart_block' ); ?>
    </div>
</div>

