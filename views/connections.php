  <div id="page" class="ui top aligned very relaxed two column stackable grid container">
 
    <div class="four wide right aligned column">
    <?php if ( wp_is_mobile() ) : ?>
      <div class="ui visible thin bottom sidebar inverted teal menu">
    <?php else : ?>
      <div class="ui secondary vertical pointing green menu">
    <?php endif; ?>
        <a href="<?php echo home_url( 'home' ); ?>" class="item"> Home</a>
        <a href="<?php echo home_url( 'home/mygroups' ); ?>" class="item"> Groups</a>
        <a href="<?php echo home_url( 'home/activities' ); ?>" class="item"> Activities</a>
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
          <div class="sub header"> Your network of friends and runners everywhere.</div>
        </div>
      </h2>      
      <?php if( count( $this->connections ) > 0 ) :
              foreach ( $this->connections as $connection ) :

                $user_id = $connection->member_id;
                $thumb_file = get_user_meta( $user_id, 'pr_member_thumbnail_image', true );
                if( empty( $thumb_file ))
                  $thumb_file = 'wireframe.png';

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
            <div class="extra">
              <span class="small-caps">
                <?php
                  $total_connections = get_user_meta( $user_id, 'total_connections', true );

                  if( empty( $total_connections ))
                      $total_connections = 0;
                                     
                  $total_connections > 1 ? $s = ' connections' : $s = ' connection';
                  echo number_format( $total_connections ) . $s;

                ?></span>
            </div>
          </div>
        </div>
        <?php endforeach;
          endif; ?>
      </div>
    </div>
  </div>
