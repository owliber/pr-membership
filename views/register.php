<div class="ui two column very relaxed stackable grid container">
  <div class="eight wide middle aligned column">

    <?php if ( isset( $attributes['errors'] ) && count( $attributes['errors'] ) > 0 ) : ?>
              <div class="ui error message">
                  <i class="close icon"></i>
                  <div class="header">
                     <?php echo $attributes['errors']; ?>
                  </div>                
              </div>
      <?php endif; ?>

      <?php if ( isset( $attributes['success'] ) &&  count( $attributes['success'] ) > 0 ) : ?>
          <div class="ui success message">
                <i class="close icon"></i>
                <div class="header">
                   <?php echo $attributes['success']; ?>
                </div>                
            </div>
      <?php endif; ?>

     <div class="ui form">

             <!-- Signup Form -->
            <form method="post" class="ui form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">

                <h1 class="ui <?php echo wp_is_mobile() ? 'medium' : 'large'; ?> header">
                  <div class="content">
                    Join us runner. Register now!
                  </div>
                </h1>
                <div class="field">
                    <div class="ui left icon input">
                      <input name="username" type="text" class="form-control login-field"
                           value="<?php echo(isset($_POST['username']) ? $_POST['username'] : null); ?>"
                           placeholder="Username" id="username" autocomplete="off" required/>
                           <i class="user icon"></i>
                    </div>
                </div>

                <div class="field">
                    <div class="ui left icon input">
                      <input name="email" type="email" class="form-control login-field"
                           value="<?php echo(isset($_POST['email']) ? $_POST['email'] : null); ?>"
                           placeholder="Email" id="email" autocomplete="off" required/>
                           <i class="mail icon"></i>
                    </div>
                </div>

                <div class="field">
                    <div class="ui left icon input">
                      <input name="password" type="password" class="form-control login-field"
                           value="<?php echo(isset($_POST['password']) ? $_POST['password'] : null); ?>"
                           placeholder="Password" id="password" autocomplete="off" required/>
                           <i class="lock icon"></i>
                    </div>
                </div>
                <div class="field">
                  <?php echo do_shortcode( '[bws_google_captcha]' ); ?>
                </div>

                <button class="fluid ui green button" id="register" type="submit" name="register">Register</button>
                
            </form>
            
            <div class="ui hidden divider"></div>

            <div class="ui message">
              <p>By signing up, you agree to our <a href="#">Terms and Conditions</a> and <a href="<?php echo home_url( 'privacy' ); ?>">Privacy Policy</a>.</p>
            </div>

        </div> <!-- ui form -->

        
  </div>
  <div class="ui vertical divider"><i class="ph flag"></i></div>

  <div class="six wide middle aligned column">
        <a class="ui labeled icon blue huge button" href="<?php echo home_url( 'login' ); ?>">
            <i class="unlock alternate icon"></i>
            Login using your account
        </a>
        <div class="ui horizontal divider">or</div>
          <?php do_action('facebook_login_button'); ?>

  </div>
</div>
