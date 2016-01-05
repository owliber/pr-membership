
<!-- Facebook Like Plugin -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=1726152354286226";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Facebook Like Plugin -->

<?php $profile = get_userdata( $this->member_id ); ?>

<div id="page" class="ui top aligned very relaxed transparent stackable grid container">
  <div class="ui <?php echo $this->headline_position; ?> floated five wide column inverted <?php echo $this->headline_color; ?> segment">
  	   <h1 class="ui header">          
          <?php if( $this->is_public( 'show_name' )) :
                    echo get_user_meta( $this->member_id, 'first_name', true ) . ' ' . get_user_meta( $this->member_id, 'last_name', true )  ;
                else :
                    echo $profile->display_name; 
                endif; 
          ?>
        <?php if( $this->is_public( 'show_name' )) : ?>
        <span class="sub header">
          <?php  echo $profile->display_name; ?>
        </span>
        <?php endif; ?>
        </h1>
        <p><?php echo get_user_meta( $this->member_id, 'description', true ) ;
         ?></p>    	
       
          <div class="ui list">

            <?php if ( get_user_meta( $this->member_id, 'gender', true ) != "" && $this->is_public( 'show_gender' ) ): ?>
            <div class="item">
              <i class="heterosexual icon"></i>
                <div class="content">
                 <?php get_user_meta( $this->member_id, 'gender', true ) == 1 ? $gender = 'Male' : $gender =  'Female'; echo $gender; ?>
               </div>
             </div>  
           <?php endif; ?>

           <?php if ( $this->is_public( 'show_birthday') ) : ?>
            <div class="item">
              <i class="birthday icon"></i>
                <div class="content">
                 <?php

                   $birthday =  date('F d, Y', mktime(0, 0, 0, get_user_meta( $this->member_id, 'birth_month', true ), get_user_meta( $this->member_id, 'birth_day', true ), get_user_meta( $this->member_id, 'birth_year', true ) ) );
                  
                   if ( $this->is_public( 'show_birthyear' ) ) {
                      echo $birthday;
                   } else {
                      echo str_replace(",", "", date('F d, ', strtotime( $birthday ))) ;
                   }

                   if ( $this->is_public( 'show_age' ) ) {
                      echo ' &mdash; '. $this->age . ' years old ';
                   }
                 ?>
               </div>
             </div>  
           <?php endif; ?>

           <?php if ( $this->is_public( 'show_weight' ) ): ?>
            <div class="item">
              <i class="dashboard icon"></i>
                <div class="content">
                 <?php echo 'Wt '. get_user_meta( $this->member_id, 'weight', true ) . 'kg'; ?>
                 <?php if ( $this->is_public( 'show_height' ) ) {
                    echo '&mdash; Ht '. get_user_meta( $this->member_id, 'height', true ) . 'm';
                 } ?>
               </div>
             </div>  
           <?php endif; ?>

            <?php if ( get_user_meta( $this->member_id, 'location', true ) != "" && $this->is_public( 'show_location' ) ) : ?>
            <div class="item">
              <i class="marker icon"></i>
                <div class="content">
                 <?php echo get_user_meta( $this->member_id, 'location', true ); ?>
               </div>
             </div>  
           <?php endif; ?>

          </div> <!-- ui list -->

          <?php if ( ! PR_Membership::is_member_page() && ! $this->is_connected() ) : ?>
          <!-- Connect -->
          <div class="ui left labeled button" tabindex="1">
            <a id="total_connections" class="ui basic right pointing label">
              <?php echo get_user_meta( $this->member_id, 'total_connections', true ); ?>
            </a>
            <?php if ( is_user_logged_in() ) : ?>
            <button id="btn_connect" class="ui teal button" value="<?php echo $this->member_id; ?>">
              <i class="user icon"></i> Connect
            </button>
            <input type="hidden" name="request_status" id="request_status" value="<?php echo $this->has_pending_request(); ?>">
            <?php else : ?>
              <a class="ui teal button" href="<?php echo home_url( 'register' ); ?>">
                <i class="user icon"></i> Connect
              </a>
            <?php endif; ?>
            
          </div>          
        <?php endif; ?>
        
        <!-- facebook likes -->
        <div class="fb-like" data-href="<?php echo esc_url(CURRENT_URI); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
        <!-- facebook likes -->

        <?php if ( $this->is_public( 'show_website' ) || $this->is_public( 'show_facebook' ) || $this->is_public( 'show_twitter' ) || $this->is_public( 'show_instagram' )  ) : ?>
        <div class="ui horizontal divider"> 
          <i class="linkify icon"></i> links
        </div>
          <?php 
          $interests = get_user_meta( $this->member_id, 'interests', true );
          if( $interests != ""  && is_array( $interests ) && $this->is_public( 'show_interests' ) ) : ?>
          <div class="ui list">
            <i class="pin icon"></i> 
            <?php 
              foreach( $interests as $interest ) : ?>
              <a href="search?tag=<?php echo $interest; ?>" class="ui mini label"><?php echo $interest; ?></a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        	<div class="ui inverted list">
            <?php if( ! empty( $profile->user_url ) && $this->is_public( 'show_website' ) ) : ?>
            <div class="item">
              <i class="globe icon"></i>
                <div class="content">
        	       <a href="<?php echo $profile->user_url; ?>" target="_blank"><?php echo $profile->user_url; ?></a>
               </div>
             </div>          
            <?php endif; ?>

            <?php if( get_user_meta( $this->member_id, 'facebook', true ) != "" && $this->is_public( 'show_facebook' ) ) : ?>
            <div class="item">
              <i class="facebook icon"></i>
                <div class="content">
                  <a href="http://www.facebook.com/<?php echo str_replace("/", "", get_user_meta( $this->member_id, 'facebook', true )); ?>" target="_blank"><?php echo get_user_meta( $this->member_id, 'facebook', true ); ?></a>
                </div>
             </div>
            <?php endif; ?>
        	 
            <?php if( get_user_meta( $this->member_id, 'twitter', true ) != "" && $this->is_public( 'show_twitter' ) ) { ?>
            <div class="item">
              <i class="twitter icon"></i>
                <div class="content">
        	       <a href="http://www.twitter.com/<?php echo str_replace("/", "", get_user_meta( $this->member_id, 'twitter', true )); ?>" target="_blank"><?php echo get_user_meta( $this->member_id, 'twitter', true );  ?></a>
               </div>
             </div>
            <?php } ?>

            <?php if( get_user_meta( $this->member_id, 'instagram', true ) != "" && $this->is_public( 'show_instagram' ) ) { ?>
            <div class="item">
              <i class="instagram icon"></i>
                <div class="content">
        	       <a href="http://www.instagram.com/<?php echo str_replace("/", "", get_user_meta( $this->member_id, 'instagram', true )); ?>" target="_blank"><?php echo get_user_meta( $this->member_id, 'instagram', true ); ?></a>
               </div>
             </div>
            <?php } ?>

          </div> <!-- ui inverted list -->
      <?php endif; ?> 

  </div>
</div> <!-- ui container -->

<!-- Statistics -->

<?php if ( count( $this->activities( true ) ) > 0 || ( PR_Membership::is_member_page() )  ) : ?>

<div id="page" class="topgradient semitransparent">
  <div class="ui data stackable relaxed grid container">

      <div class="ui <?php echo $this->headline_color; ?> statistics">
        <!-- Statistics -->
        <div class="statistic">
          <div class="value">
            <?php echo get_user_meta( $this->member_id, 'year_started_running', true ); ?>
          </div>
          <div class="label">
            Running since
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo number_format( $this->total_races()->total, 0 ); ?>
          </div>
          <div class="label">
            Races
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo ( ! empty( $this->statistic()->activity_count ) ) ? number_format( $this->statistic()->activity_count, 0 ) : 0 ; ?>
          </div>
          <div class="label">
            Ran Made
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo ( !empty( $this->statistic()->total_distance ) ) ? number_format( $this->statistic()->total_distance, 0 ) : 0; ?>
          </div>
          <div class="label">
            Kilometers
          </div>
        </div>
       <!--  <div class="statistic">
          <div class="value">
            <php echo ( !empty( $this->statistic()->total_calories )) ? number_format( $this->statistic()->total_calories, 0 ) : 0; ?>
          </div>
          <div class="label">
            Calories
          </div>
        </div> -->
        <!-- <div class="statistic">
          <div class="value">
            <php echo ( !empty( $this->statistic()->total_elev_gain ) ) ? number_format( $this->statistic()->total_elev_gain, 0 ) : 0; ?>
          </div>
          <div class="label">
            Elevation
          </div>
        </div> -->
        <div class="statistic">
          <div class="value">
            <?php echo ( !empty( $this->statistic()->total_time ) ) ?  $this->statistic()->total_time : 0; ?>
          </div>
          <div class="label">
            Total Time
          </div>
        </div>
      </div>

  </div>
  <div class="ui stackable grid container">
    <table class="ui tablet stackable inverted grey table">
      <thead>
        <tr>
          <th class="collapsing">Activity</th>
          <th class="collapsing">Activity Type</th>
          <th class="collapsing">Date</th>
          <th class="collapsing">Distance (K)</th>
          <th class="collapsing">Time</th>
          <th class="collapsing">Avg. Pace (min/km)</th>
          <!-- <th class="collapsing">Calories</th>
          <th class="collapsing">Elev Gain (m)</th> -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $this->activities() as $row ) : ?>
        <tr>
          <td><?php echo $row->activity_name; ?></td>
          <td><?php echo ucfirst($row->activity_type); ?></td>
          <td><?php echo date('F d, Y',strtotime($row->activity_date)); ?></td>          
          <td><?php echo $row->distance; ?></td>
          <td><?php echo $row->total_time; ?></td>
          <td><?php echo $row->average_pace; ?></td>
         <!--  <td><php echo $row->calories; ?></td>
          <td><php echo $row->elevation_gain; ?></td> -->
        </tr>
      <?php endforeach; ?>        
      </tbody>

      <!-- Table Footer Option For Logged User -->      
      <tfoot class="full-width">
        <tr>
          <th></th>
          <th colspan="8">
              <!-- Modal button -->
              <?php if( PR_Membership::is_member_page() ) : ?>
                <div id="btn_new_activity" class="ui right floated small teal labeled icon button">
                  <i class="heartbeat icon"></i> New Activity
                </div>
              <?php endif; ?>

               <div id="btn_viewall_activity" class="ui right floated small teal labeled icon button">
                <i class="filter icon"></i>Show All Activities
              </div>
          </th>
        </tr>
      </tfoot>

    </table>
  </div>
</div> <!-- Statistics -->

<!-- Modal Form -->
<div id="new_activity" class="ui modal">
  <div class="header">
    Add your new activity
  </div>
  <div class="content">
    <form id="frm_activity" class="ui equal width form" method="post" action="">
      <div class="required field">
          <label>Activity Name</label>
          <input id="activity_name" name="activity[activity_name]" placeholder="e.g Seaside Running" type="text" value="" required>
      </div>
      <div class="fields">
        <div class="required field">
          <label>Activity Type</label>
          <select name="activity[activity_type]" class="ui fluid dropdown" required>
              <option value="Training">Training</option>
              <option value="Race">Race</option>
          </select>
        </div>
        <div class="required field">
            <label>Activity Date</label>
            <div class="ui left icon input">              
            <input id="activity_date" name="activity[activity_date]" type="text" value="<?php echo date('Y-m-d'); ?>" required>
              <i class="calendar icon"></i>
            </div> 
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#activity_date').daterangepicker({
                        singleDatePicker: true,
                        parentEl: 'div.ui.modal',
                        drops: 'up',
                        opens: 'right',
                        format: 'YYYY-MM-DD',
                    }, function(start, end, label) {
                        console.log(start.toISOString(), end.toISOString(), label);
                    });
                });
            </script>        
        </div>
      </div>
      <div class="fields">
        <div class="required field">
          <label>Distance (K)</label>
          <input id="distance" name="activity[distance]" type="text" value="0" required>
        </div>
        <div class="required field">
          <label>Time (hh:mm:ss)</label>
          <input id="total_time" class="timepicker" name="activity[total_time]" type="text" value="00:00:00" required>
        </div>
        <div class="required field">
          <label>Avg. Pace (min/km)</label>
          <input id="average_pace" name="activity[average_pace]" type="text" value="00:00" readonly>
        </div>
        <!-- <div class="required field">
          <label>Calories (C)</label>
          <input name="activity[calories]" type="text" value="0" required>
        </div>
        <div class="align right required field">
          <label>Elev Gain (m)</label>
          <input name="activity[elev_gain]" type="text" value="0" required>
        </div> -->
      </div>      
       <div class="field">
        <label>Notes</label>
        <textarea name="activity[notes]" rows="2"></textarea>
      </div>
      <div class="ui buttons">
        <button id="btn_cancel" type="button" class="ui default button">Cancel</button>
        <button type="submit" class="ui teal positive right labeled icon button">Save Activity <i class="checkmark icon"></i></button>
      </div>
    </form>
  </div>
</div>

<!-- Message Modal -->
<div id="all-activities" class="ui long fullscreen modal">
  <div class="header">
    All Activities
  </div>
  <div class="content">
    <table class="ui very basic stackable table">
      <thead>
        <tr>
          <th>Activity</th>
          <th>Activity Type</th>
          <th>Date</th>
          <th>Distance (K)</th>
          <th>Time</th>
          <th>Avg. Pace (min/km)</th>
          <!-- <th>Calories</th>
          <th>Elev Gain (m)</th> -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $this->activities( false ) as $row ) : ?>
        <tr>
          <td><?php echo $row->activity_name; ?></td>
          <td><?php echo ucfirst($row->activity_type); ?></td>
          <td><?php echo date('F d, Y',strtotime($row->activity_date)); ?></td>          
          <td><?php echo $row->distance; ?></td>
          <td><?php echo $row->total_time; ?></td>
          <td><?php echo $row->average_pace; ?></td>
          <!-- <td><php echo $row->calories; ?></td>
          <td><php echo $row->elevation_gain; ?></td> -->
        </tr>
      <?php endforeach; ?>        
      </tbody>
    </table>
  </div>
  <div class="actions">
    <div id="btn_all_activities" class="ui teal button">Close</div>
  </div>
</div>

<?php endif; ?>
