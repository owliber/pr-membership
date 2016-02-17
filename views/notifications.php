  <div class="ui two column top aligned very relaxed stackable grid container">
    
    <div class="four wide right aligned column">
      <?php if ( wp_is_mobile() ) : ?>
        <div class="ui visible thin bottom sidebar inverted teal menu">
      <?php else : ?>
        <div class="ui secondary vertical pointing green menu">
      <?php endif; ?>
        <a href="<?php echo home_url( 'settings/profile' ); ?>" class="item"> Profile</a>
        <a href="<?php echo home_url( 'settings/account' ); ?>" class="item"> Account</a>
        <a href="<?php echo home_url( 'settings/privacy' ); ?>" class="item"> Privacy</a>
        <a class="active item"> Notifications</a>
      </div>
    </div>
    
    <div class="twelve wide left aligned column">
      <?php if ( isset( $result['errors'] ) && count( $result['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <div class="ui error message">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if ( isset( $result['success'] ) &&  $result['success'] ) : ?>
         <div class="ui success message">
            <p class="lead">
            <?php echo 'Notification settings was successfully changed.'; ?>
            </p>
        </div>
     <?php endif; ?>

      <form class="ui large equal width form" method="post" action="">
        <h2 class="ui left aligned header">
          <i class="alarm icon"></i>
          <div class="content">
            Notifications
            <div class="sub header"> Choose the notifications you would like to receive via email.</div>
          </div>
        </h2>
          <div class="ui hidden divider"></div>
          <div class="inline field">
            <div class="ui checkbox">
              <input class="hidden" tabindex="0" type="checkbox">
              <label>Someone connect with me</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input class="hidden" tabindex="0" type="checkbox">
              <label>Someone compliments my page</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input class="hidden" tabindex="0" type="checkbox">
              <label>Someone share my page</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input class="hidden" tabindex="0" type="checkbox">
              <label>Someone faves your page</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input class="hidden" tabindex="0" type="checkbox">
              <label>Someone invited me to a group</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input class="hidden" tabindex="0" type="checkbox">
              <label>Someone liked your activity</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui checkbox">
              <input class="hidden" tabindex="0" type="checkbox">
              <label>New events and other updates</label>
            </div>
          </div>
          <div class="ui divider"></div>
          <button class="ui green button" name="submit" value="save" type="submit">Save</button>
      </form>   
    </div>
  </div>