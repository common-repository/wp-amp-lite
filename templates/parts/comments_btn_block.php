<?php
$url = $this->get_canonical_url();
if ( $this->options->get( 'mobile_amp' ) ) {
    $url = add_query_arg( array(
        'view-original-redirect' => '1',
    ), $url );
}
$section = $this->get_section();
?>
<div class="amp-button-holder">
    <a href="<?php echo $url ?>#comments"
       class="amp-button"><?php esc_html_e( $this->options->get( $section . '_comments_btn_text' ), 'amphtml' ) ?></a>
</div>