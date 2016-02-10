<div id="page" class="ui top aligned very relaxed stackable grid container">
   <div class="eight wide centered column">
        <div class="ui segments">
            <div class="ui raised segment">
                
                    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
                        <?php foreach ( $attributes['errors'] as $error ) : ?>
                            <div class="ui error message">
                                <i class="close icon"></i>
                                <div class="header">
                                   <?php echo $error; ?>
                                </div>                
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                <div class="ui form">
                    <h2>Reset Your Password</h2>
                    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
                        
                        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
                        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />
                                         
                        <div class="ui field">
                            <label for="pass1"><?php _e( 'New password', 'pinoyrunners' ) ?></label>
                            <input type="password" name="pass1" id="pass1" size="20" value="" autocomplete="off" required>
                        </div>
                        <div class="ui field">
                            <label for="pass2"><?php _e( 'Repeat new password', 'pinoyrunners' ) ?></label>
                            <input type="password" name="pass2" id="pass2" size="20" value="" autocomplete="off" required>
                        </div>
                        <div class="ui field">
                            <?php echo do_shortcode( '[bws_google_captcha]' ); ?>
                        </div>
                         
                        <p class="description"><?php echo wp_get_password_hint(); ?></p>                         
                        
                        <input class="ui button" type="submit" name="submit" id="resetpass-button" value="<?php _e( 'Reset Password', 'pinoyrunners' ); ?>" />
                        
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
