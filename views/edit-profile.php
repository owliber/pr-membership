  <div id="page" class="ui two column top aligned very relaxed stackable grid container">
    
    <div class="four wide right aligned column">
      <div class="ui secondary vertical pointing green menu">
        <a class="active item"> Profile</a>
        <a href="<?php echo home_url( 'settings/account' ); ?>" class="item"> Account</a>
        <a href="<?php echo home_url( 'settings/privacy' ); ?>" class="item"> Privacy</a>
        <a href="<?php echo home_url( 'settings/notification' ); ?>" class="item"> Notifications</a>
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
            <p class="lead">Ayos! You successfully updated your profile.</p>
        </div>
     <?php endif; ?>

      <form class="ui large equal width form" method="post" action="">
        
      <h2 class="ui left aligned header">
        <!-- <img class="ui image" src="/images/icons/school.png"> -->
        <i class="user icon"></i>
        <div class="content">
          Profile
          <div class="sub header"> Update your profile and other information you want to share with the community.</div>
        </div>
      </h2>
        <div class="ui hidden divider"></div>
        <div class="field">
          <div class="ten wide field">
            <label>Display Name</label>
            <input name="profile[display_name]" placeholder="e.g The Road Eater, Pinay Mamaw, Hot Chick Runner" type="text" value="<?php echo $this->display_name;  ?>">
          </div>
        </div>
        <div class="fields">
          <div class="required field">
            <label>First Name</label>
            <input name="profile[first_name]" placeholder="First Name" type="text" value="<?php echo $this->first_name;  ?>" required>
          </div>
          <div class="required field">
            <label>Last Name</label>
            <input name="profile[last_name]" placeholder="Last Name" type="text" value="<?php echo $this->last_name; ?>" required>
          </div>
        </div>

        <div class="required field">
          <label>Say something cool about you!</label>
          <textarea name="profile[description]" rows="4" required><?php echo $this->description; ?></textarea>
        </div>
        <div class="field">
          <label>What other sports you do?</label>
          <select name="profile[other_sports][]" multiple="" class="ui fluid dropdown">
            <?php 
            if( count( $this->other_sports ) > 0 ) : 
              foreach ($this->ref_sports as $sport)
              {

                in_array( $sport->sport_name, $this->other_sports ) ? $selected = " selected" : $selected = "";
                echo '<option '.$selected.' value="'.$sport->sport_name.'">'.$sport->sport_name.'</option>';

              }
            endif;
            ?>
          </select>
        </div>
        <div class="field">
          <div class="ui search"
            <label>What are your interests?</label>
            <select name="profile[interests][]" multiple="" class="ui fluid search dropdown">
              <?php 
              if( count( $this->interests ) > 0 ) :
                foreach ($this->ref_interests as $interest)
                {

                  in_array( $interest->interest_name, $this->interests ) ? $selected = " selected" : $selected = "";
                  echo '<option '.$selected.' value="'.$interest->interest_name.'">'.$interest->interest_name.'</option>';

                }
              endif;
              ?>
            </select>           
          </div>
        </div>         
        <div class="fields">          
          <div class="field">
            <label>Gender</label>
            <select name="profile[gender]" class="ui search dropdown" required>
              <?php 
                $this->gender == 1 ? $select_male = 'selected' : $select_male = '';
                $this->gender == 2 ? $select_female = 'selected' : $select_female = '';
              ?>
              <option value="">Gender</option>
              <option <?php echo $select_male; ?> value="1">Male</option>
              <option <?php echo $select_female; ?> value="2">Female</option>
            </select>
          </div>                   
          <div class="field">
            <label>Height (m)</label>
            <input name="profile[height]" placeholder="e.g 1.79" type="text" value="<?php echo $this->height; ?>">
          </div>
          <div class="field">
            <label>Weight (kg)</label>
            <input name="profile[weight]" placeholder="e.g 60" type="text" value="<?php echo $this->weight; ?>">
          </div>
        </div>
        <div class="required fields">
          <div class="field">                
          <label>Birthday</label>
            <select name="profile[birth_day]" class="ui search dropdown" required>
              <option value="">Day</option>
              <?php 
                for ( $i = 1; $i <= 31; $i++) {
                  if( $this->birth_day == $i )
                    echo '<option selected value="'.$i.'">'.$i.'</option>';
                  else
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
              ?>
            </select>
          </div>          
          <div class="field">
            <label>Month</label>
            <select name="profile[birth_month]" class="ui search dropdown" required>
              <option value="">Month</option>
              <?php 
                foreach( $this->months as $key => $val ) {
                  if ( $this->birth_month == $key )
                    echo '<option selected value="'.$key.'">'.$val.'</option>';
                  else
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
              ?>
            </select>
          </div>
          <div class="field">  
          <label>Year</label>          
            <select name="profile[birth_year]" class="ui search dropdown" required>               
              <option value="">Year</option>           
              <?php 
                for ( $j = CUR_YEAR - MIN_AGE; $j >= CUR_YEAR - MAX_AGE; $j--) {
                  if ( $this->birth_year == $j )
                    echo '<option selected value="'.$j.'">'.$j.'</option>';
                  else
                    echo '<option value="'.$j.'">'.$j.'</option>';
                }
              ?>
            </select>
          </div>
        </div>
        <div class="required fields">          
          <div class="field">
            <label>Location</label>
            <input name="profile[location]" placeholder="e.g Rosario, Pasig City" type="text" value="<?php echo $this->location; ?>">
          </div>
          <div class="field">
            <label>What year you started running?</label>
            <input name="profile[year_started_running]" placeholder="Year" type="text" value="<?php echo $this->year_started_running; ?>" maxlength="4" required>
          </div>
        </div>
        <h3 class="ui header">
          <i class="world icon"></i>
          <div class="content">
            Social Links
          </div>
        </h3>
        <div class="ui divider"></div>
        <div class="eight wide field">
          <label>Facebook </label>
          <div class="ui left icon input">
            <i class="facebook icon"></i>
            <input name="profile[facebook]" placeholder="cherry.devera" type="text" value="<?php echo $this->facebook; ?>">
          </div>
        </div>
        <div class="eight wide field">
          <label>Twitter </label>
          <div class="ui left icon input">
            <i class="twitter icon"></i>
              <input name="profile[twitter]" placeholder="cherry_red" type="text" value="<?php echo $this->twitter; ?>">
          </div>
        </div>
        <div class="eight wide field">
          <label>Instagram</label>
          <div class="ui left icon input">
            <i class="instagram icon"></i>
            <input name="profile[instagram]" placeholder="cherry_red" type="text" value="<?php echo $this->instagram; ?>">
          </div>
        </div>
        <div class="eight wide field">
          <label>Site</label>
          <input name="profile[user_url]" placeholder="Link to your blog or website" type="text" value="<?php echo $this->user_url; ?>">
        </div>


        <!-- <div class="ui search">
        <div class="ui icon input">
          <input class="prompt" placeholder="Search countries..." type="text">
          <i class="search icon"></i>
        </div>
        <div class="results"></div>
      </div> -->

        <button class="ui green button" type="submit">Update Profile</button>
    </form>     
    </div>

  </div>