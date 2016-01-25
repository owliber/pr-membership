
<div id="page" class="ui two column top aligned very relaxed stackable grid container">
 
  <div class="four wide right aligned column">
    <div class="ui secondary vertical pointing green menu">
      <a class="active item"> Home</a>
      <a href="<?php echo home_url( 'home/mygroups' ); ?>" class="item"> Groups</a>
      <a href="<?php echo home_url( 'home/activities' ); ?>" class="item"> Activities</a>
      <a href="<?php echo home_url( 'home/connections' ); ?>" class="item"> Connections</a>
      <a href="<?php echo home_url( 'home/events-joined' ); ?>" class="item"> Events Joined</a>
    </div>
  </div><!-- four wide right aligned column -->

   <div class="twelve wide column">  

      <?php if( get_user_meta( $this->user_id, 'has_profile_background', true ) == 0 ) : ?>
      <div class="ui small warning message">
        <p> You don't have a profile background yet. Upload one of your best running shot now. <a href="<?php echo home_url( 'member/'.$this->username );?>">Edit your page and upload a background here.</a></p>
      </div>
      <?php endif; ?>
      <?php if( get_user_meta( $this->user_id, 'is_profile_update', true ) == 0 ) : ?>
      <div class="ui small warning message">
        <p> Update your profile details so you will be recognized by other runners. <a href="<?php echo home_url( 'home'); ?>">Click here to update.</a></p>
      </div>
      <?php endif; ?>

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
      <?php
            //Blog Posts
            $args = array(
                'posts_per_page'   => 25,
                'offset'           => 0,     
                'post_type'        => array( 'post', 'events'),
                'post_status'      => 'publish',
            );

            $posts = new WP_Query( $args ); 

            if ( $posts->have_posts() ) : ?>
              <div class="ui divided items">

                <?php while( $posts->have_posts() ) :
                       setup_postdata( $posts ); 
                       $posts->the_post();
                ?>
                <div class="item">
                  <a class="ui small image" href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) {                  
                        echo get_the_post_thumbnail();
                    }else {
                      echo '<img src="'.WP_CONTENT_URL.'/uploads/thumbnail/wireframe.png">';
                    }
                    ?>
                  </a>
                  <div class="content">              
                    <a class="header" href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                    <div class="description">                
                      <p>
                      <?php 
                        //Remote the attached image
                        $content = get_the_content();
                        $content = preg_replace("/<img[^>]+\>/i", " ", $content);          
                        $content = apply_filters('the_content', $content);
                        $content = str_replace(']]>', ']]>', $content);
                        $content = preg_replace('/^\s+|\n|\r|\s+$/m', '', $content);
                        $content = wp_trim_words($content, 50, ' ...');
                        
                        echo $content;

                      ?></p>
                    </div>
                    <?php $post_type = get_post_type();  
                      if ( $post_type == 'events' ) :
                    ?>
                    <div class="extra">
                      Posted in <labe class="ui small label"><?php echo ucfirst( $post_type ); ?></label>
                    </div>
                  <?php endif; ?>
                  </div>
                </div>
              <?php
              endwhile;
              wp_reset_postdata(); ?>
            </div>
            <?php
            endif;

      ?>

      <?php if ( count( $requests ) == 0 && count( $group_requests ) == 0  && count( $posts ) == 0 ): ?>
        <div class="ui info message">
            <div class="header">
               No more feeds at the moment.
            </div>                
        </div>
      <?php endif; ?>
    </div><!-- column -->
    
</div><!-- page -->


