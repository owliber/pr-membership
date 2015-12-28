  <div id="page" class="ui two column top aligned very relaxed stackable grid container">
 
    <div class="four wide right aligned column">
      <div class="ui secondary vertical pointing green menu">
        <a href="<?php echo home_url( 'home' ); ?>" class="item"> Home</a>
        <a href="<?php echo home_url( 'home/mygroups' ); ?>" class="item"> Groups</a>
        <a class="active item"> Connections</a>
        <a href="<?php echo home_url( 'home/events-joined' ); ?>" class="item"> Events Joined</a>
      </div>
    </div>

     <div class="twelve wide column">
      <div class="ui divided items">
      <h2 class="ui left aligned header">
        <i class="sitemap icon"></i>
          <div class="content">
          Connections
          <div class="sub header"> Your network of friends and runners around the globe</div>
        </div>
      </h2>      
      <?php if( count( $this->connections ) > 0 ) :
              foreach ( $this->connections as $connection ) :

                $user_id = $connection->member_id;
                $thumb_file = get_user_meta( $user_id, 'pr_member_thumbnail_image', true );
                $thumbnail = THUMB_DIR . '/'.$thumb_file;
                $member = get_userdata( $user_id ); 
        ?>
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
            </div>
            <div class="description">
              <p><?php echo wp_trim_words(get_user_meta( $user_id, 'description', true ), 40, '...'); ?></p>
            </div>
          </div>
        </div>
        <?php endforeach;
          endif; ?>
      </div>
    </div>
  </div>
