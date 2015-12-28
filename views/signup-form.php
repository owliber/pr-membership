 <?php if ( isset( $attributes['errors'] ) && count( $attributes['errors'] ) > 0 ) : ?>
      <div class="ui error message fade">
          <i class="close icon"></i>
          <div class="header">
             <?php echo $attributes['errors']; ?>
          </div>                
      </div>
<?php endif; ?>

<?php if ( isset( $attributes['success'] ) &&  count( $attributes['success'] ) > 0 ) : ?>
  <div class="ui success message fade">
        <i class="close icon"></i>
        <div class="header">
           <?php echo $attributes['success']; ?>
        </div>                
    </div>
<?php endif; ?>

<form method="post" class="ui form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">

    <h3 class="ui header">Join us runners!</h3>
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

    <input class="ui green button" type="submit" name="register" value="Register"/>
	
</form>