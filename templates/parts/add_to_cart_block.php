<?php $section = $this->get_section();?>
<div class="clearfix">
    <?php if ( $this->get_option( $section . '_price' ) ): ?>
        <p class="amphtml-price"><?php woocommerce_template_loop_price(); ?></p>
    <?php endif; ?>

    <?php if ( $this->get_option( $section . '_add_to_cart' ) ): ?>
        <?php AMPHTML_WC()->get_add_to_cart_button(); ?>
    <?php endif; ?>
</div>