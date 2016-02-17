<div class="ui top aligned very relaxed two column stackable grid container">
 
    <div class="four wide right aligned column">
    <?php if ( wp_is_mobile() ) : ?>
      <div class="ui visible thin bottom sidebar inverted teal menu">
    <?php else : ?>
      <div class="ui secondary vertical pointing green menu">
    <?php endif; ?>
        <a href="<?php echo home_url( 'home' ); ?>" class="item"> Home</a>
        <a href="<?php echo home_url( 'home/mygroups' ); ?>" class="item"> Groups</a>
        <a href="<?php echo home_url( 'home/activities' ); ?>" class="item"> Activities</a>
        <a href="<?php echo home_url( 'home/connections' ); ?>" class="item"> Connections</a>
        <a class="active item"> Events Joined</a>
      </div>
    </div>

     <div class="twelve wide column">
        <div class="ui divided items"> 
          <h2 class="ui left aligned header">
          <i class="calendar icon"></i>
            <div class="content">
              My Events
            <div class="sub header"> Lists of all race events you recently joined.</div>
          </div>
        </h2>
          <?php 
              $post_per_page = 25;

                  $args = array(
                    'posts_per_page'   => $post_per_page, 
                    'post_type'        => 'events',
                    'post_status'      => 'publish',
                    'include'          => $this->events_joined,
                    'meta_key'         => 'race_date',
                    'orderby'          => 'meta_value_num',
                    'order'            => 'ASC'
                  );

              $events = get_posts( $args ); 

          ?>
          <?php if ( count( $events ) > 0 ) : ?>            
          <?php foreach ( $events as $event ) : ?>
          <div class="item">
            <a class="ui small image" href="<?php echo home_url( 'events/' . $event->post_name ); ?>">
              <?php if ( has_post_thumbnail( $event->ID ) ) {                  
                  echo get_the_post_thumbnail( $event->ID );
              }else {
                
                echo '<img src="'.WP_CONTENT_URL.'/uploads/thumbnail/wireframe.png">';
              }
              ?>
            </a>
            <div class="content">
              <a class="header" href="<?php echo home_url( 'events/' . $event->post_name ); ?>"><?php echo $event->post_title; ?> </a>
              <div class="meta">
                <span class="date"><i class="calendar outline icon"></i> <?php echo date('F d, Y',strtotime(get_post_meta( $event->ID, 'race_date', true ))); ?></span><br />  
              </div>
              <div class="meta">
                <span class="location"><i class="marker icon"></i> <?php echo get_post_meta( $event->ID, 'location', true ); ?></span><br />                
              </div>
              <div class="meta">
                <span class="distance"><i class="road icon"></i> <?php echo implode("/", get_post_meta( $event->ID, 'distance', true )) ; ?></span>  
              </div>
              <div class="description">                
                <p>
                <?php 
                  //Remote the attached image
                  $content = $event->post_content;
                  $content = preg_replace("/<img[^>]+\>/i", " ", $content);          
                  $content = apply_filters('the_content', $content);
                  $content = str_replace(']]>', ']]>', $content);
                  $content = preg_replace('/^\s+|\n|\r|\s+$/m', '', $content);
                  $content = wp_trim_words($content, 40, ' ...');                  
                  echo $content;

                ?></p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php else: ?>

          <div class="ui info message">
            <i class="close icon"></i>
            <div class="header">
              You have not joined any events yet. Please vist our lists of new <a href="<?php echo home_url( 'events' ); ?>"> events</a> here.
            </div>
          </div>
        
        <?php endif; ?>
      </div> <!-- items -->
    </div> <!-- column 2 -->

</div> <!-- page -->
