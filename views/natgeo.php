<div class="ui divided items">
<h3 class="ui left aligned header">
    <div class="content">
    <?php echo $attributes['valid'] . ' valid out of ' . number_format($attributes['total']) . ' total entries'; ?> <!-- as of <php echo date('F d, Y h:i A',strtotime(CUR_DATE + '8 HOUR')); ?> -->
  </div>
</h3>      
<?php if( count( $attributes['entries'] ) > 0 ) :
        foreach ( $attributes['entries'] as $entry ) :

          $user_id = $entry->ID;
          $thumb_file = get_user_meta( $user_id, 'pr_member_thumbnail_image', true );
          if( empty( $thumb_file ))
            $thumb_file = 'wireframe.png';

          $thumbnail = THUMB_DIR . '/'.$thumb_file;
          $member = get_userdata( $user_id ); 
  ?>
  <div class="item">
    <a class="ui tiny image" href="<?php echo home_url( 'member/'.$entry->user_login ); ?>">
      <img src="<?php echo $thumbnail; ?>">
    </a>
    <div class="content">
      <a class="header" href="<?php echo home_url( 'member/'.$entry->user_login ); ?>">
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

          ?> | Registered on <?php echo date('M d h:i A',strtotime($entry->user_registered)); ?></span><br />
          
      </div>
      <div class="description">
            <?php echo wp_trim_words(get_user_meta( $user_id, 'description', true ), 10, '...'); ?>
      </div>
      <div class="extra">
        <span class="small-caps">
          <?php
            $total_connections = get_user_meta( $user_id, 'total_connections', true );

            if( empty( $total_connections ))
                $total_connections = 0;
                               
            $total_connections > 1 ? $s = ' connections' : $s = ' connection';
            echo number_format( $total_connections ) . $s;

          ?> | <?php echo $entry->total_activities; ?> rans</span>
      </div>
    </div>
  </div>
  <?php endforeach;
    endif; ?>
    
</div>
