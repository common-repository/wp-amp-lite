<?php if ( have_posts() ): ?>
    <h1 class="amphtml-title">
        <?php printf( esc_html__( 'Search Results for: %s', 'amphtml' ), '<span>' . get_search_query() . '</span>' ); ?>
    </h1>
<?php else: ?>
    <h1 class="amphtml-title"><?php esc_html_e( 'Nothing Found', 'amphtml' ); ?></h1>
<?php endif; ?>