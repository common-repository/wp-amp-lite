<?php
/**
 * The Template for displaying a message that posts cannot be found
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/search-none.php.
 *
 * @var $this AMPHTML_Template
 */
if ( is_search() ) :
    ?>
    <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'amphtml' ); ?></p>
<?php else : ?>
    <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'amphtml' ); ?></p>
<?php
endif;

echo $this->render( 'searchform' );
