<div id="page" class="ui top aligned very relaxed stackable grid container">
   <div class="eight wide centered column">
        <div class="ui segments">
            <div class="ui raised segment">
                
                    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
                        <?php foreach ( $attributes['errors'] as $error ) : ?>
                            <div class="ui error message fade">
                                <i class="close icon"></i>
                                <div class="header">
                                   <?php echo $error; ?>
                                </div>                
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ( isset( $attributes['lost_password_sent'] )) : ?>
                         <div class="ui info message">
                            <div class="header">
                                <?php _e( $attributes['lost_password_sent'], 'pinoyrunners' ); ?>
                            </div>                            
                        </div>
                    <?php endif; ?>

                    <?php if ( isset( $attributes['password_updated'] )) : ?>
                         <div class="ui success message">
                            <div class="header">
                                 <?php _e( $attributes['password_updated'], 'pinoyrunners' ); ?>
                            </div>                            
                        </div>
                    <?php endif; ?>

                <div class="ui form">
                    <h1 class="ui <?php echo wp_is_mobile() ? 'medium' : 'large'; ?> header">Login to pinoyrunners.co</h1>
                    <div class="field">
                    <?php
                        wp_login_form(
                            array(
                                'label_username' => __( '', 'pr-login' ),
                                'label_password' => __( '', 'pr-login' ),
                                'label_log_in' => __( 'Login', 'pr-login' ),
                                'redirect' => $attributes['redirect'],
                                'remember'       => false,
                            )
                        );
                    ?>    

                    </div>
                    <div class="field">
                        <?php do_action('facebook_login_button'); ?>
                    </div>

                    <div class="field">

                        <div class="ui toggle checkbox">
                            <input class="hidden" tabindex="0" name="rememberme" id="rememberme" value="forever" type="checkbox">
                            <label>Remember me <span> &middot; <a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a></span></label> 
                        </div>
                    </div>
                </div>

            </div>

            <div class="ui secondary segment">
                <p>New to pinoyrunners.co? <a href="<?php echo home_url( 'register' ); ?>">Register now &raquo;</a></p>
            </div>
        </div>
    </div>
</div>

