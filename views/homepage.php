
<div id="page" class="ui two column top aligned very relaxed stackable grid container">
 
  <div class="four wide right aligned column">
    <div class="ui secondary vertical pointing green menu">
      <a class="active item"> Home</a>
      <a href="<?php echo home_url( 'home/mygroups' ); ?>" class="item"> Groups</a>
      <a href="<?php echo home_url( 'home/connections' ); ?>" class="item"> Connections</a>
      <a href="<?php echo home_url( 'home/events-joined' ); ?>" class="item"> Events Joined</a>
    </div>
  </div><!-- four wide right aligned column -->

   <div class="twelve wide column">   
      <?php  

      $requests = $this->connection_requests();

      if( count( $requests ) > 0 ) :
            foreach ( $requests as $request ) :

              isset ( $request->member_id ) ? $user_id = $request->member_id : $user_id = $request->user_id;
      
              $thumb_file = get_user_meta( $user_id, 'pr_member_thumbnail_image', true );
              $thumbnail = THUMB_DIR . '/'.$thumb_file;
              $member = get_userdata( $user_id ); 
      ?>
      <div class="ui divided items"> 
        <h4>Connections</h4>
      <div class="item">
        <a class="ui small image" href="<?php echo home_url( 'member/'.$member->user_login ); ?>">
          <img src="<?php echo $thumbnail; ?>">
        </a>
        <div class="content">
          <a class="header" href="<?php echo home_url( 'member/'.$member->user_login ); ?>">
          <?php 
            $first_name = get_user_meta( $user_id, 'first_name', true ); 
            $last_name = get_user_meta( $user_id, 'last_name', true ); 
            $login_name = ucfirst( $member->user_login );

            if ( !empty( $first_name ) || !empty( $last_name ))
              echo $first_name . ' ' . $last_name;
            else 
              echo $login_name;

          ?></a>          

          <div class="meta">
            <span class="year">
              <?php
                $year_started_running = get_user_meta( $user_id, 'year_started_running', true );

                if( !empty( $year_started_running ))
                    $meta = 'Running since ' . $year_started_running;
                else {
                  if ( !empty( $member->display_name ))
                    $meta = $member->display_name;
                  else 
                    $meta = "";
                }

                echo $meta;

              ?></span>
          </div><!-- meta -->

          <div class="description">
            <p><?php echo wp_trim_words(get_user_meta( $user_id, 'description', true ), 40, '...'); ?></p>
          </div>

            <div class="extra">
              <?php if ( isset( $request->is_acknowledged ) ) : ?>
                <button id="btn_acknowledge" name="acknowledge" class="ui positive primary button btn-ack-<?php echo $user_id; ?>" value="<?php echo $user_id; ?>">
                  Ok
                </button>
                <span class="ack-message-<?php echo $user_id; ?>"></span>
              <?php else : ?>
                <button id="btn_accept" name="accept_request" class="ui positive primary button btn-accept-<?php echo $user_id; ?>" value="<?php echo $user_id; ?>">
                  Accept
                </button>
                <button id="btn_ignore" name="ignore_request" class="ui negative red button btn-ignore-<?php echo $user_id; ?>" value="<?php echo $user_id; ?>">
                  Later
                </button>              
                <br />
                <span class="accepted-message-<?php echo $user_id; ?>"></span>
                <span class="ignored-message-<?php echo $user_id; ?>"></span>               
              <?php endif; ?>
              <span class="error-message-<?php echo $user_id; ?>" ></span>
            </div><!-- extra -->
          </div><!-- content -->
        </div><!-- item -->
      </div><!-- divided items -->
      <?php endforeach;
      endif; ?>

      <!-- Group Join Requests -->
      <?php 
        $group_requests = $this->group_requests();
        if ( count( $group_requests ) > 0 ) : ?>                  
        <div class="ui divided items">
            <h4>Group Joins</h4>
          <?php 

             foreach ( $group_requests as $group ) :

                $user_id = $group->user_id;
                $group_id = $group->group_id;
        
                $thumb_file = get_user_meta( $user_id, 'pr_member_thumbnail_image', true );
                $thumbnail = THUMB_DIR . '/'.$thumb_file;
                $member = get_userdata( $user_id ); 

          ?>

          <div class="item">
            <a class="ui small image" href="<?php echo home_url( 'member/'.$member->user_login ); ?>">
              <img src="<?php echo $thumbnail; ?>">
            </a>
            <div class="middle aligned content">
              <a class="header" href="<?php echo home_url( 'member/'.$member->user_login ); ?>">
              <?php 
                $first_name = get_user_meta( $user_id, 'first_name', true ); 
                $last_name = get_user_meta( $user_id, 'last_name', true ); 
                $login_name = ucfirst( $member->user_login );

                if ( !empty( $first_name ) || !empty( $last_name ))
                  echo $first_name . ' ' . $last_name;
                else 
                  echo $login_name;

              ?></a>
              <div class="meta">
              <span class="year">
                <?php
                  $year_started_running = get_user_meta( $user_id, 'year_started_running', true );

                  if( !empty( $year_started_running ))
                      $meta = 'Running since ' . $year_started_running;
                  else {
                    if ( !empty( $member->display_name ))
                      $meta = $member->display_name;
                    else 
                      $meta = "";
                  }

                  echo $meta;

                ?></span>
            </div><!-- meta -->
              <div class="description">
                <p><?php echo wp_trim_words(get_user_meta( $user_id, 'description', true ), 40, '...'); ?></p>
              </div>
              <div class="extra">
                <button id="btn_group_accept" name="accept_group_request" class="ui positive primary button btn-group-accept-<?php echo $user_id; ?>" value="<?php echo $user_id; ?>">
                    Add To Group
                </button>
                <button id="btn_group_ignore" name="ignore_group_request" class="ui negative red button btn-group-ignore-<?php echo $user_id; ?>" value="<?php echo $user_id; ?>">
                  Later
                </button>
                <input type="hidden" id="group-id-<?php echo $user_id; ?>" name="group_id" value="<?php echo $group_id; ?>">
                <span class="group-request-message-<?php echo $user_id; ?>" ></span>
                <span class="group-error-message-<?php echo $user_id; ?>" ></span>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <!-- End group join requests -->

      <?php if ( count( $requests ) == 0 || count( $group_requests ) == 0 ) : ?>
        <div class="ui info message">
            <div class="header">
               No more feeds at the moment.
            </div>                
        </div>
      <?php endif; ?>
    </div><!-- column -->
    
</div><!-- page -->


