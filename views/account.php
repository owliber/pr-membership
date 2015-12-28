  <div id="page" class="ui two column top aligned very relaxed stackable grid container">
    
    <div class="four wide right aligned column">
      <div class="ui secondary vertical pointing green menu">
        <a href="<?php echo home_url( 'settings/profile' ); ?>" class="item"> Profile</a>
        <a class="active item"> Account</a>
        <a href="<?php echo home_url( 'settings/privacy' ); ?>" class="item"> Privacy</a>
        <a href="<?php echo home_url( 'settings/notifications' ); ?>" class="item"> Notifications</a>
      </div>
    </div>
    
    <div class="twelve wide left aligned column">
      <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <div class="ui error red inverted strong message">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if ( $attributes['success'] ) : ?>
         <div class="ui success green inverted message">
            <p class="lead">
            <?php if ( $attributes['success'] == 1 ) {
                    echo 'Boss, ayos secure na yan!.';
                  } else {
                    echo 'We sent you an email for verification, please check your email.';
                  }?>
            </p>
        </div>
     <?php endif; ?>

      <form class="ui large equal width form" method="post" action="">
        <h2 class="ui left aligned header">
          <i class="lock icon"></i>
          <div class="content">
            Account
            <div class="sub header"> You can change your password or email address anytime.</div>
          </div>
        </h2>
          <div class="ui hidden divider"></div>
            <div class="eight wide field">
              <label>Username</label>
              <div class="ui disabled input">              
                <input name="account[user_login]" type="text" value="<?php echo $this->user_login; ?>">
              </div>
            </div>
            <div class="eight wide required field">
              <label>Current Password</label>
              <input name="account[current_password]" type="password" value="" autcomplete="off" required>
            </div>
            <div class="eight wide required field">
              <label>New Password</label>
              <input name="account[new_password]" type="password" value="" autcomplete="off" required>
            </div>
            <div class="eight wide required field">
              <label>Confirm Password</label>
              <input name="account[confirm_password]" type="password" value="" autcomplete="off" required>
            </div>
          <button class="ui green button" name="submit" value="1" type="submit">Change Password</button>
      </form>   
      <div class="ui hidden divider"></div>
        <?php if ( !$this->is_verified ) : ?>
        <div class="ui warning message">
          <i class="close icon"></i>
          <div class="header">
            Email Confirmation Needed!
          </div>
          Your email address is still unverified, please kindly check your inbox and confirm your email address. You can also resend the verification link if needed.
        </div>
        <?php endif; ?>
        <form class="ui large equal width form" method="post" action="">
            <div class="ten wide required field">
              <label> Email Address</label>  
               <div class="ui icon input">         
                  <input name="account[email]" type="email" value="<?php echo $this->email; ?>" required>
                  <?php if ( $this->is_verified ) : ?>
                    <i class="green check circle icon"></i>
                  <?php else : ?>
                    <i class="gray check circle icon"></i>
                  <?php endif; ?>
              </div>
            </div>
            <button class="ui green button" name="submit" value="2" type="submit">Update Email</button>
            <?php if ( !$this->is_verified ) : ?>
            <button class="ui primary button" name="submit" value="3" type="submit">Resend Verification Link</button>
            <?php endif; ?>
        </form>
    </div>
  </div>