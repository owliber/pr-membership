
    <!-- Show errors if there are any -->
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

    <!-- Show logged out message if user just logged out -->
    <?php if ( $attributes['logged_out'] ) : ?>
         <div class="ui success message fade">
            <i class="close icon"></i>
            <div class="header">
               You logged out.
            </div>
        </div>
    <?php endif; ?>

    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php _e( 'Sign In', 'pr-login' ); ?></h2>
    <?php endif; ?>
    
    <div class="ui form">
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
      
      <div class="ui horizontal divider">Or</div>
          <div class="field">
            <?php do_action('facebook_login_button'); ?>
          </div>
     
        <div class="field">
            <div class="ui toggle checkbox">
                <input class="hidden" tabindex="0" name="rememberme" id="rememberme" value="forever" type="checkbox">
                <label>Remember me <span> &mdash; <a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a></span></label> 
            </div>
        </div>
     
        
    <!-- wp_lostpassword_url(); -->
    
    </div><!-- ui form -->
