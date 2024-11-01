<?php if ( $this->options->get( 'social_instagram' ) || $this->options->get( 'social_facebook' ) || $this->options->get( 'social_twitter' ) || $this->options->get( 'social_linkedin' ) || $this->options->get( 'social_pinterest' ) || $this->options->get( 'social_youtube' ) ) { ?>
    <nav class="amp-social">
        <ul>
            <?php if ( $this->options->get( 'social_instagram' ) ) { ?>
                <li>
                    <a href="<?php echo $this->options->get( 'social_instagram' ); ?>" class="amp-social-link amp-social-instagram" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
                            <g>
                                <path d="M24,0H6A6,6,0,0,0,0,6V24a6,6,0,0,0,6,6H24a6,6,0,0,0,6-6V6A6,6,0,0,0,24,0Zm3,26a1,1,0,0,1-1,1H4a1,1,0,0,1-1-1V13H9.35a6,6,0,0,1,11.3,0H27ZM27,8a1,1,0,0,1-1,1H22a1,1,0,0,1-1-1V4a1,1,0,0,1,1-1h4a1,1,0,0,1,1,1Z"/>
                                <path d="M22.48,10H18.31a6,6,0,1,1-6.62,0H7.52a9,9,0,1,0,15,0Z"/>
                            </g>
                        </svg>
                    </a>
                </li>
            <?php } ?>
            <?php if ( $this->options->get( 'social_facebook' ) ) { ?>
                <li>
                    <a href="<?php echo $this->options->get( 'social_facebook' ); ?>" class="amp-social-link amp-social-facebook" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 30">
                            <g>
                                <polygon points="13 16 0 16 0 10 14 10 13 16"/>
                                <path d="M3,6V30H9V7a.9.9,0,0,1,1-1h4V0H9.39C2.87,0,3,5.26,3,6Z"/>
                            </g>
                        </svg>
                    </a>
                </li>
            <?php } ?>
            <?php if ( $this->options->get( 'social_twitter' ) ) { ?>
                <li>
                    <a href="<?php echo $this->options->get( 'social_twitter' ); ?>" class="amp-social-link amp-social-twitter" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 24">
                            <g>
                                <path d="M30,3a12.1,12.1,0,0,1-3.53.79A6.47,6.47,0,0,0,29,1c-1.19.69-2.34.64-3.74.91A6.21,6.21,0,0,0,20.77,0a6.11,6.11,0,0,0-6.16,6.06,5.73,5.73,0,0,0,.17,1.38A17.85,17.85,0,0,1,2,1a6.28,6.28,0,0,0-.75,3.16A6,6,0,0,0,4,9.2a6.24,6.24,0,0,1-2.79-.76v.08a6.09,6.09,0,0,0,4.93,5.94,6.15,6.15,0,0,1-1.62.21,6.34,6.34,0,0,1-1.16-.11A6.24,6.24,0,0,0,9,19c-2.63,3-8.52,2.05-9,2a17.87,17.87,0,0,0,9.44,3A17.25,17.25,0,0,0,27,6.76c0-.26,0-.52,0-.78A11.92,11.92,0,0,0,30,3Z"/>
                            </g>
                        </svg>
                    </a>
                </li>
            <?php } ?>
            <?php if ( $this->options->get( 'social_linkedin' ) ) { ?>
                <li>
                    <a href="<?php echo $this->options->get( 'social_linkedin' ); ?>" class="amp-social-link amp-social-linkedin" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 28">
                            <g>
                                <rect y="9" width="6" height="19"/>
                                <path d="M30,28H24V18c0-3-1.46-4-3.53-4S17,15.08,17,18V28H11s.08-17.43,0-19h6v3c.47-1.52,2.68-3,5.89-3,4,0,7.11,2.71,7.11,8.53Z"/><path class="cls-1" d="M0,2.58A3,3,0,1,0,3.42,0,3,3,0,0,0,0,2.58Z"/>
                            </g>
                        </svg>
                    </a>
                </li>
            <?php } ?>
            <?php if ( $this->options->get( 'social_pinterest' ) ) { ?>
                <li>
                    <a href="<?php echo $this->options->get( 'social_pinterest' ); ?>" class="amp-social-link amp-social-pinterest" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.81 30">
                            <g>
                                <path d="M12.63,0C4.17,0,0,5.87,0,10.76c0,3,1.06,5.6,3.55,6.59a.62.62,0,0,0,.89-.43c.08-.31.28-1.07.36-1.39a.81.81,0,0,0-.25-1,4.86,4.86,0,0,1-1.18-3.38c0-4.35,3.37-8.25,8.77-8.25,4.78,0,7.41,2.83,7.41,6.6,0,5-2.27,9.46-5.65,9.46a2.92,2.92,0,0,1-2.81-3.61c.54-2.19,1.81-4.54,1.81-6.12C12.9,7.86,12,7,10.26,7,8.07,7,6.9,8.59,6.9,11.15a7.16,7.16,0,0,0,.5,2.72s-2.12,8-2.29,9.39a34.74,34.74,0,0,0-.06,6.55.24.24,0,0,0,.43.1,18.32,18.32,0,0,0,3.21-5.64c.22-.77,1.25-4.74,1.25-4.74A5.38,5.38,0,0,0,14.29,22c5.73,0,9.52-5.37,9.52-12.13C23.81,4.76,19.43,0,12.63,0Z"/>
                            </g>
                        </svg>
                    </a>
                </li>
            <?php } ?>
            <?php if ( $this->options->get( 'social_youtube' ) ) { ?>
                <li>
                    <a href="<?php echo $this->options->get( 'social_youtube' ); ?>" class="amp-social-link amp-social-youtube" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 20">
                            <g>
                                <path d="M25,0H5A5,5,0,0,0,0,5V15a5,5,0,0,0,5,5H25a5,5,0,0,0,5-5V5A5,5,0,0,0,25,0ZM19.48,10.92l-8.15,4.86c-.86.51-1.33.1-1.33-.92V5.14c0-1,.45-1.43,1.31-.92l8.16,4.84A1.09,1.09,0,0,1,19.48,10.92Z"/>
                            </g>
                        </svg>
                    </a>
                </li>
            <?php } ?>        
            <ul>
                </nav>
                <?php
            }?>