
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

<?php  
  $view = new PR_Profile;
  $mid = $view->get_MID();
  $uid = get_current_user_id();
  $profile = get_userdata( $mid ); 

?>

   <div id="page" class="ui top aligned very relaxed transparent stackable grid container">
    <div class="ui <?php echo $profile->pr_member_headline_position; ?> floated five wide column inverted <?php echo $profile->pr_member_headline_color; ?> segment">
    	   <h1 class="ui header">          
            <?php if( ! $view->is_private( 'show_name' )) :
                      echo $profile->first_name . ' ' . $profile->last_name;
                  else :
                      echo $profile->display_name; 
                  endif; 
            ?>
          <span class="sub header">
            <?php  echo $profile->display_name; ?>
          </span>
          </h1>
          <p><?php echo $profile->description;
           ?></p>    	
         
            <div class="ui list">

              <?php if ( ! empty( $profile->gender ) && ! $view->is_private( 'show_gender' ) ): ?>
              <div class="item">
                <i class="heterosexual icon"></i>
                  <div class="content">
                   <?php echo $profile->gender; ?>
                 </div>
               </div>  
             <?php endif; ?>

             <?php if ( ! empty( $profile->birthday ) && ! $view->is_private( 'show_birthday') ) : ?>
              <div class="item">
                <i class="birthday icon"></i>
                  <div class="content">
                   <?php 
                     if ( ! $view->is_private( 'show_birthyear' ) ) {
                        echo $profile->birthday . ', ' . $profile->birth_year;
                     } else {
                        echo $profile->birthday;
                     }

                     if ( ! $view->is_private( 'show_age' ) ) {
                        echo ' &mdash; '. $profile->age . ' years old ';
                     }
                   ?>
                 </div>
               </div>  
             <?php endif; ?>

             <?php if ( ! $view->is_private( 'show_weight' ) ): ?>
              <div class="item">
                <i class="dashboard icon"></i>
                  <div class="content">
                   <?php echo 'Wt '. $profile->weight . 'kg'; ?>
                   <?php if ( $profile->show_height ) {
                      echo '&mdash; Ht '. $profile->height . 'm';
                   } ?>
                 </div>
               </div>  
             <?php endif; ?>

              <?php if ( ! empty( $profile->location ) && $view->is_private( 'show_location' ) ) : ?>
              <div class="item">
                <i class="marker icon"></i>
                  <div class="content">
                   <?php echo $profile->location; ?>
                 </div>
               </div>  
             <?php endif; ?>

            </div> <!-- ui list -->

            <?php if ( ! PR_Membership::is_member_page() && ! $view->is_connected( $mid, $uid ) ) : ?>
            <!-- Connect -->
            <div class="ui left labeled button" tabindex="1">
              <a id="total_connections" class="ui basic right pointing label">
                <?php echo $profile->total_connections; ?>
              </a>
              <?php if ( is_user_logged_in() ) : ?>
              <button id="btn_connect" class="ui teal button" value="<?php echo $view->member_id; ?>">
                <i class="user icon"></i> Connect
              </button>
              <input type="hidden" name="request_status" id="request_status" value="<?php echo $profile->has_pending_request( $mid, $uid ); ?>">
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

          <?php if ( ! $view->is_private( 'show_website' ) || ! $view->is_private( 'show_facebook' ) || ! $view->is_private( 'show_twitter' ) || ! $view->is_private( 'show_instagram' )  ) : ?>
          <div class="ui horizontal divider"> 
            <i class="linkify icon"></i> links
          </div>
            <?php if( ! empty( $profile->interests ) && is_array( $profile->interests ) ) : ?>
            <div class="ui list">
              <i class="pin icon"></i> 
              <?php foreach( $profile->interests as $interest ) : ?>
                <a href="search?tag=<?php echo $interest; ?>" class="ui mini label"><?php echo $interest; ?></a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          	<div class="ui inverted list">
              <?php if( ! empty( $profile->user_url ) && ! $view->is_private( 'show_website' ) ) : ?>
              <div class="item">
                <i class="globe icon"></i>
                  <div class="content">
          	       <a href="<?php echo $profile->user_url; ?>" target="_blank"><?php echo $profile->user_url; ?></a>
                 </div>
               </div>          
              <?php endif; ?>

              <?php if( ! empty( $profile->facebook ) && ! $view->is_private( 'show_facebook' ) ) : ?>
              <div class="item">
                <i class="facebook icon"></i>
                  <div class="content">
                    <a href="http://www.facebook.com/<?php echo str_replace("/", "", $profile->facebook); ?>" target="_blank"><?php echo $profile->facebook; ?></a>
                  </div>
               </div>
              <?php endif; ?>
          	 
              <?php if( ! empty( $profile->twitter ) && ! $view->is_private( 'show_twitter' ) ) { ?>
              <div class="item">
                <i class="twitter icon"></i>
                  <div class="content">
          	       <a href="http://www.twitter.com/<?php echo str_replace("/", "", $profile->twitter); ?>" target="_blank"><?php echo $profile->twitter;  ?></a>
                 </div>
               </div>
              <?php } ?>

              <?php if( ! empty( $profile->instagram ) && ! $view->is_private( 'show_instagram' ) ) { ?>
              <div class="item">
                <i class="instagram icon"></i>
                  <div class="content">
          	       <a href="http://www.instagram.com/<?php echo str_replace("/", "", $profile->instagram); ?>" target="_blank"><?php echo $profile->instagram; ?></a>
                 </div>
               </div>
              <?php } ?>

            </div> <!-- ui inverted list -->
        <?php endif; ?> 

    </div>
  </div> <!-- ui container -->

<!-- Statistics -->

<?php if ( count( $view->activities( $mid, true ) ) > 0 || ( PR_Membership::is_member_page() )  ) : ?>

<div id="page" class="topgradient semitransparent">
  <div class="ui data stackable relaxed grid container">

      <div class="ui <?php echo $profile->pr_member_headline_color; ?> statistics">
        <!-- Statistics -->
        <div class="statistic">
          <div class="value">
            <?php echo $profile->year_started_running; ?>
          </div>
          <div class="label">
            Running since
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo number_format( $view->total_races( $mid )->total, 0 ); ?>
          </div>
          <div class="label">
            Races
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo ( ! empty( $view->statistic( $mid )->activity_count ) ) ? number_format( $view->statistic( $mid )->activity_count, 0 ) : 0 ; ?>
          </div>
          <div class="label">
            Ran Made
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo ( !empty( $view->statistic( $mid )->total_distance ) ) ? number_format( $view->statistic( $mid )->total_distance, 0 ) : 0; ?>
          </div>
          <div class="label">
            Kilometers
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo ( !empty( $view->statistic( $mid )->total_calories )) ? number_format( $view->statistic( $mid )->total_calories, 0 ) : 0; ?>
          </div>
          <div class="label">
            Calories
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo ( !empty( $view->statistic( $mid )->total_elev_gain ) ) ? number_format( $view->statistic( $mid )->total_elev_gain, 0 ) : 0; ?>
          </div>
          <div class="label">
            Elevation
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            <?php echo ( !empty( $view->statistic( $mid )->total_time ) ) ?  $view->statistic( $mid )->total_time : 0; ?>
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
          <th class="collapsing">Calories</th>
          <th class="collapsing">Elev Gain (m)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $view->activities( $mid ) as $row ) : ?>
        <tr>
          <td><?php echo $row->activity_name; ?></td>
          <td><?php echo ucfirst($row->activity_type); ?></td>
          <td><?php echo date('F d, Y',strtotime($row->activity_date)); ?></td>          
          <td><?php echo $row->distance; ?></td>
          <td><?php echo $row->total_time; ?></td>
          <td><?php echo $row->average_pace; ?></td>
          <td><?php echo $row->calories; ?></td>
          <td><?php echo $row->elevation_gain; ?></td>
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
        <div class="required field">
          <label>Calories (C)</label>
          <input name="activity[calories]" type="text" value="0" required>
        </div>
        <div class="align right required field">
          <label>Elev Gain (m)</label>
          <input name="activity[elev_gain]" type="text" value="0" required>
        </div>
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
          <th>Calories</th>
          <th>Elev Gain (m)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $view->activities( $mid, false ) as $row ) : ?>
        <tr>
          <td><?php echo $row->activity_name; ?></td>
          <td><?php echo ucfirst($row->activity_type); ?></td>
          <td><?php echo date('F d, Y',strtotime($row->activity_date)); ?></td>          
          <td><?php echo $row->distance; ?></td>
          <td><?php echo $row->total_time; ?></td>
          <td><?php echo $row->average_pace; ?></td>
          <td><?php echo $row->calories; ?></td>
          <td><?php echo $row->elevation_gain; ?></td>
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
