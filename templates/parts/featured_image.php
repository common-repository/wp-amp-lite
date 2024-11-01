<?php

if ( $this->is_featured_image() ):
    echo $this->render_element( 'image', $this->featured_image );
 endif;