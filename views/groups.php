<div id="page" class="ui container">

        <div class="ui relaxed divided items"> 
          <h2 class="ui left aligned header">
          <i class="users icon"></i>
            <div class="content">
            Groups
            <div class="sub header"> Join a team or group to meet new friends.</div>
          </div>
        </h2>
          <?php 

              $args = array(
                'posts_per_page'   => 5,
                'offset'           => 0,                
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => 'groups',
                'post_status'      => 'publish',
                'suppress_filters' => true 
              );

              $groups = get_posts( $args ); 

          ?>
          <?php if ( count( $groups ) > 0 ) : ?>            
          <?php foreach ( $groups as $group ) : setup_postdata( $post ) ;?>

          <div class="item">
            <a class="image" href="<?php the_permalink(); ?>">
              <img src="">
            </a>
            <div class="content">
              
              <a class="header" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              <div class="meta">
                <span class="location"></span>                
              </div>

              <div class="description">                
                <p><?php echo the_content(); ?></p>
              </div>
              
              <div class="extra">                
                <span class="member_count">222</span>
                <button name="btn_join_group" id="btn_join_group" class="ui teal right floated default button" value="<?php echo $group->ID; ?>">Join This Group</button>
              </div>

            </div>
          </div>
          <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>
          <?php else: ?>
          
          <div class="ui info message">
            <i class="close icon"></i>
            <div class="header">
              There are no groups yet. Go to your homepage and start creating your group now.
            </div>
          </div>

        <?php endif; ?>
      </div> <!-- items -->
</div> <!-- page -->
