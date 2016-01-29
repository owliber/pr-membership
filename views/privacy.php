  <div id="page" class="ui two column top aligned very relaxed stackable grid container">
    
    <div class="four wide right aligned column">
      <div class="ui secondary vertical pointing green menu">
        <a href="<?php echo home_url( 'settings/profile' ); ?>" class="item"> Profile</a>
        <a href="<?php echo home_url( 'settings/account' ); ?>" class="item"> Account</a>
        <a class="active item"> Privacy</a>
        <a href="<?php echo home_url( 'settings/notifications' ); ?>" class="item"> Notifications</a>
      </div>
    </div>
    
    <div class="twelve wide left aligned column">
      <?php if ( isset( $result['errors'] ) && count( $result['errors'] ) > 0 ) : ?>
        <?php foreach ( $result['errors'] as $error ) : ?>
            <div class="ui error message">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if ( isset( $result['success'] ) && $result['success'] ) : ?>
         <div class="ui success message">
            <p class="lead">
            <?php echo 'Your privacy settings is now updated.'; ?>
            </p>
        </div>
     <?php endif; ?>

      <form class="ui large equal width form" method="post" action="">
        <h2 class="ui left aligned header">
          <i class="protect icon"></i>
          <div class="content">
            Privacy
            <div class="sub header"> You can show or hide here any information you want to disclose to the community.</div>
          </div>
        </h2>
          <div class="ui hidden divider"></div>

          <h3>Profile</h3>
          <div class="ui divider"></div>
          <?php if ( isset( $this->first_name ) && isset( $this->last_name )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="0" type="checkbox" values="1" name="privacy[show_name]" value="1" <?php if ($this->show_name) echo 'checked'; ?> >
              <label>Show first name and last name as display name <strong class="ui tag label"><?php echo $this->first_name . ' ' . $this->last_name; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->gender )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="1" type="checkbox" name="privacy[show_gender]" value="1" <?php if ($this->show_gender) echo 'checked'; ?>>
              <label>Show your gender <strong class="ui tag label"><?php echo $this->gender; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->location )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="2" type="checkbox" name="privacy[show_location]" value="1" <?php if ($this->show_location) echo 'checked'; ?>>
              <label>Show your location <strong class="ui tag label"><?php echo $this->location; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->birth_month ) && isset( $this->birth_day )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="3" type="checkbox" name="privacy[show_birthday]" value="1" <?php if ($this->show_birthday) echo 'checked'; ?>>
              <label>Show your birthday <strong class="ui tag label"><?php echo date('F',strtotime($this->birth_month)) . ' ' . $this->birth_day; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->birth_year )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="3" type="checkbox" name="privacy[show_birthyear]" value="1" <?php if ($this->show_birthyear) echo 'checked'; ?>>
              <label>Show your birth year <strong class="ui tag label"><?php echo $this->birth_year; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->age )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="4" type="checkbox" name="privacy[show_age]" value="1" <?php if ($this->show_age) echo 'checked'; ?>>
              <label>Show your age <strong class="ui tag label"><?php echo $this->age .' years old'; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->year_started_running )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="5" type="checkbox" name="privacy[show_year_started_running]" value="1" <?php if ($this->show_year_started_running) echo 'checked'; ?>>
              <label>Show the year you started running <strong class="ui tag label"><?php echo $this->year_started_running; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->height )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="6" type="checkbox" name="privacy[show_height]" value="1" <?php if ($this->show_height) echo 'checked'; ?>>
              <label>Show your height <strong class="ui tag label"><?php echo $this->height . 'm'; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->weight )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="7" type="checkbox" name="privacy[show_weight]" value="1" <?php if ($this->show_weight) echo 'checked'; ?>>
              <label>Show your weight <strong class="ui tag label"><?php echo $this->weight . 'kg'; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->facebook )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="8" type="checkbox" name="privacy[show_facebook]" value="1" <?php if ($this->show_facebook) echo 'checked'; ?>>
              <label>Show your facebook account <strong class="ui tag label"><?php echo $this->facebook; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->twitter )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="9" type="checkbox" name="privacy[show_twitter]" value="1" <?php if ($this->show_twitter) echo 'checked'; ?>>
              <label>Show your twitter account <strong class="ui tag label"><?php echo $this->twitter; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->instagram )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="10" type="checkbox" name="privacy[show_instagram]" value="1" <?php if ($this->show_instagram) echo 'checked'; ?>>
              <label>Show your instagram <strong class="ui tag label"><?php echo $this->instagram; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->user_url ) && !empty( $this->user_url )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="11" type="checkbox" name="privacy[show_website]" value="1" <?php if ($this->show_website) echo 'checked'; ?>>
              <label>Show your blog or website <strong class="ui tag label"><?php echo $this->user_url; ?></strong></label>
            </div>
          </div>
          <?php endif; ?>
          <?php if ( isset( $this->interests )) : ?>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="11" type="checkbox" name="privacy[show_interests]" value="1" <?php if ($this->show_interests) echo 'checked'; ?>>
              <label>Show your interests 
                <?php
                  foreach( $this->interests as $interest ) {
                    echo '<a class="ui tag label">'.$interest.'</a> ';
                  }
                ?>
              </label>
            </div>
          </div>
          <?php endif; ?>
          
          <h3>Activities</h3>
          <div class="ui divider"></div>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="12" type="checkbox" name="privacy[show_fastest_pace]" value="1" <?php if ($this->show_fastest_pace) echo 'checked'; ?>>
              <label>Show your fastest pace</label>
            </div>
          </div>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="16" type="checkbox" name="privacy[show_total_time]" value="1" <?php if ($this->show_total_time) echo 'checked'; ?>>
              <label>Show your total time</label>
            </div>              
          </div>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="16" type="checkbox" name="privacy[show_activity_time]" value="1" <?php if ($this->show_activity_time) echo 'checked'; ?>>
              <label>Show your activity time</label>
            </div>              
          </div>
          <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="16" type="checkbox" name="privacy[show_activity_pace]" value="1" <?php if ($this->show_activity_pace) echo 'checked'; ?>>
              <label>Show your activity pace</label>
            </div>              
          </div>
          
          <h3>Connections</h3>
          <div class="ui divider"></div>
            <div class="inline field">
            <div class="ui toggle checkbox">
              <input class="hidden" tabindex="18" type="checkbox" name="privacy[enable_connection_approval]" value="1" <?php if ($this->enable_connection_approval) echo 'checked'; ?>>
              <label>Enable connection approval</label>
            </div>              
          </div>
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <input type="hidden" name="privacy[user_id]" value="<?php echo $this->user_id; ?>">
          <button class="ui green button" name="submit" value="save" type="submit">Save</button>
      </form>   
    </div>
  </div>