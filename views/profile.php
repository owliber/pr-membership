<?php $profile = get_userdata( $this->member_id ); ?>

<div id="page" class="ui top aligned very relaxed transparent stackable grid container">
  <div id="profile" class="ui <?php echo $this->headline_position; ?> floated five wide padded column <?php echo $this->headline_color; ?> inverted segment">
    <?php if( get_user_meta( $this->member_id, 'is_featured', true ) == 1 ) : ?>
    <div class="ui green top right attached label">Featured Runner</div>
    <?php endif; ?>
    <?php if( wp_is_mobile() && PR_Membership::is_member_page() ) : ?>
     <button id="btn-edit-page" class="ui right floated red small inverted button">Edit Page</button>
    <?php endif; ?>
  	   <h1 class="ui header">          
          <?php if( $this->is_public( 'show_name' )) :
                    echo get_user_meta( $this->member_id, 'first_name', true ) . ' ' . get_user_meta( $this->member_id, 'last_name', true )  ;
                else :
                    echo $profile->display_name; 
                endif; 
          ?>
        <?php if( $this->is_public( 'show_year_started_running' )) : ?>
        <span class="sub header small-caps">
          <?php  echo 'running since ' . get_user_meta( $this->member_id, 'year_started_running', true ); ?>
        </span>
        <?php endif; ?>
        </h1>
        <?php 
            $description = get_user_meta( $this->member_id, 'description', true ) ;
            if( isset($description) && str_word_count( $description ) > 100 ) {
              $text_size = '14px';
            } else {
              $text_size = '100%';
            }
         ?>
         <span class="description"><?php echo $description; ?></span>    	
       
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
          <div class="ui left labeled mini button" tabindex="1">
            <a id="total_connections" class="ui basic right pointing label">
              <?php 
              $total_connections = get_user_meta( $this->member_id, 'total_connections', true );
              if( empty( $total_connections ))
                $total_connections = 0;
              
              echo number_format( $total_connections );
              ?>
            </a>
            <?php if ( is_user_logged_in() ) : ?>
            <button id="btn_connect" class="ui teal mini button" value="<?php echo $this->member_id; ?>">
              <i class="user icon"></i> Connect
            </button>
            <input type="hidden" name="request_status" id="request_status" value="<?php echo $this->has_pending_request(); ?>">
            <?php else : ?>
              <a class="ui teal mini button" href="<?php echo home_url( 'register' ); ?>">
                <i class="user icon"></i> Connect
              </a>
            <?php endif; ?>
          </div>          
        <?php endif; ?>

        <?php if(  $this->is_connected() ) : ?>
        <div class="ui left labeled mini button" tabindex="1">
          <a class="ui basic label">
            <?php 
              $total_connections = get_user_meta( $this->member_id, 'total_connections', true );
              if( empty( $total_connections ))
                $total_connections = 0;
              
              echo number_format( $total_connections );
              ?>            
          </a>
          <div class="ui green mini button">
              <?php $s = $total_connections > 1 ? 'Connections' : 'Connection'; echo $s; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- facebook likes echo home_url( $_SERVER['REQUEST_URI'] ); -->
        <div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
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
              <a href="<?php echo home_url( 'search/'.$interest ); ?>" class="ui mini label"><?php echo $interest; ?></a>
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
    <?php if( isset( $result ) ) echo $result; ?>
      <div class="ui <?php echo $this->headline_color; ?> large statistics">
        <!-- Statistics -->
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
        <div class="statistic">
          <div class="value">
            <?php echo $this->farthest_distance(); ?>
          </div>
          <div class="label">
            Farthest Distance (K)
          </div>
        </div>
        <?php if( $this->is_public( 'show_fastest_pace')) : ?>
        <div class="statistic">
          <div class="value">
            <?php echo date('i:s',strtotime( $this->fastest_pace() )); ?>
          </div>
          <div class="label">
            Fastest Pace (min/km)
          </div>
        </div>
        <?php endif; ?>
        <?php if( $this->is_public( 'show_total_time')) : ?>
        <div class="statistic">
          <div class="value">
            <?php echo ( !empty( $this->statistic()->total_time ) ) ?  $this->statistic()->total_time : 0; ?>
          </div>
          <div class="label">
            Total Time
          </div>
        </div>
        <?php endif; ?>
      </div>

  </div>
  <div id="data" class="ui stackable grid container">

    <!-- Personal Records -->
    <?php if ( $this->is_public( 'show_personal_records' ) ) : ?>
    <?php 
      $result = $this->personal_records(); 
      $records = array_filter($result);
      $cols = count($records);
    ?>
    <table class="ui center aligned celled small compact <?php echo $this->headline_color; ?> table">
    <thead>
      <tr>
        <th colspan="<?php echo $cols; ?>" class="small-caps"><h4>Personal Records</h4></th>
      </tr>
      <tr>
        <?php foreach( $records as $key=>$record) : ?>
        <th><?php echo $key; ?>K</th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php foreach( $records as $key=>$record) : ?>
        <td><strong style="font-size:150%"><?php echo $record; ?></strong></td>
        <?php endforeach; ?>
      </tr>
    </tbody>
  </table>
  <?php endif ?><!-- Personal Records -->

    <table class="ui tablet stackable <?php echo $this->headline_color; ?> table">
      <thead>
        <tr>
          <th class="collapsing">Activity</th>
          <th class="collapsing">Location</th>
          <th class="collapsing">Activity Type</th>
          <th class="collapsing">Date</th>
          <th class="collapsing">Distance (K)</th>
          
          <?php if( $this->is_public('show_total_time')) : ?>
            <th class="collapsing">Time</th>
          <?php endif; ?>
          
          <?php if( $this->is_public('show_activity_pace')) : ?>
            <th class="collapsing">Pace (min/km)</th>
          <?php endif; ?>
          
          <?php if( $this->is_public('show_remarks')) : ?>
          <th class="collapsing">Remarks</th>
          <?php endif; ?>

          <?php if( PR_Membership::is_member_page() ) : ?>
            <th class="collapsing" align="center"><i class="setting icon"></i></th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $this->activities( true ) as $row ) : ?>
        <tr id="row-id-<?php echo $row->activity_id; ?>">
          <td class="nowrap"><strong><?php echo $row->activity_name; ?></strong></td>
          <td class="nowrap"><strong><?php echo $row->location; ?></strong></td>
          <td><?php echo ucfirst($row->activity_type); ?></td>
          <td class="nowrap"><?php echo date('F d, Y',strtotime($row->activity_date)); ?></td>          
          <td><?php echo $row->distance; ?></td>
          
          <?php if( $this->is_public('show_total_time')) : ?>
            <td><?php echo $row->total_time; ?></td>
          <?php endif; ?>
          
          <?php if( $this->is_public('show_activity_pace')) : ?>
            <td><?php echo date('i:s',strtotime( $row->average_pace ) ); ?></td>
          <?php endif; ?>
          
          <?php if( $this->is_public('show_remarks')) : ?>
          <td class="nowrap"><?php echo $row->notes; ?></td>
          <?php endif; ?>

          <?php if( PR_Membership::is_member_page() ) : ?>
          <td>
              <button id="btn-delete" class="ui mini icon button btn-delete-<?php echo $row->activity_id;?>" value="<?php echo $row->activity_id; ?>"><i class="delete icon"></i></button>
          </td>
          <?php endif; ?>
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
                <div id="btn_new_activity" class="ui <?php echo ! wp_is_mobile() ? 'right floated' : ''; ?> small teal labeled icon button">
                  <i class="heartbeat icon"></i> Quick Add
                </div>
              <?php endif; ?>

               <div id="btn_viewall_activity" class="ui <?php echo ! wp_is_mobile() ? 'right floated' : ''; ?> small teal labeled icon button">
                <i class="filter icon"></i>Show All Activities
              </div>
          </th>
        </tr>
      </tfoot>

    </table>
  </div>
</div> <!-- Statistics -->

<!-- Modal Form -->
<div id="activity" class="ui modal">
  <div id="modal-header-activity" class="header">
    Add your new activity
  </div>
  <div class="content">
    <form id="frm_activity" class="ui equal width form" method="post" action="">
      <div class="fields">
        <div class="required field">
            <label>Activity Name</label>
            <input id="activity_name" name="activity[activity_name]" placeholder="e.g Seaside Running" type="text" value="" required>
        </div>
        <div class="required field">
            <label>Location</label>
            <input id="activity_location" name="activity[location]" placeholder="e.g Pasay City" type="text" value="" required>
        </div>
      </div>
      <div class="fields">
        <div class="required field">
          <label>Activity Type</label>
          <select id="activity_type" name="activity[activity_type]" class="ui fluid dropdown" required>
              <option value="Race">Race</option>
              <option value="Training">Training</option>
          </select>
        </div>
        <div class="required field">
            <label>Activity Date</label>
            <div class="ui left icon input">              
            <input id="activity_date" name="activity[activity_date]" type="text" value="<?php echo date('Y-m-d'); ?>" required>
              <i class="calendar icon"></i>
            </div> 
            <?php if ( is_user_logged_in() ) : ?>
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
          <?php endif; ?>
        </div>
        <div id="bib" class="ui field">
          <label>Bib Number</label>
          <input id="bibnumber" name="activity[bibnumber]" type="text" value="" placeholder="Bib #">
        </div>
      </div>
      <div class="fields">        
        <div class="required field">
          <label>Distance (K)</label>
          <input id="distance" name="activity[distance]" type="text" value="0.0" required>
        </div>
        <div class="required field">
          
            <label>Total Time (Hour)</label>
            <select id="total_hour" name="activity[total_hour]" class="ui fluid dropdown timepicker" required>
                <?php 
                for($i = 0; $i <= 23; $i++) {
                  $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                  echo '<option value="'.$i.'">'.$i.'</option>';
                }
                ?>
            </select>
          </div>
        <div class="required field">  
            <label>Minutes</label>
            <select id="total_minute" name="activity[total_minute]" class="ui fluid dropdown timepicker" required>
                <?php 
                for($j = 0; $j <= 59; $j++) {
                  $j = str_pad($j, 2, '0', STR_PAD_LEFT);
                  echo '<option value="'.$j.'">'.$j.'</option>';
                }
                ?>
            </select>
          
        </div>
        <div class="required field">
          <label>Avg. Pace (min/km)</label>
          <input id="average_pace" name="activity[average_pace]" type="text" value="00:00" readonly>
        </div>
      </div>      
       <div class="field">
        <label>Add some notes and share us your experience.</label>
        <textarea id="description" name="activity[notes]" rows="3"></textarea>
      </div>
              
        <button type="submit" class="ui teal positive right labeled icon right floated button">Save Activity <i class="checkmark icon"></i></button>
        <button id="btn_cancel" type="button" class="ui right floated default button">Cancel</button>
      
      <div class="ui hidden divider"></div>
      <div class="ui hidden divider"></div>
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
          <th>Location</th>
          <th>Activity Type</th>
          <th>Date</th>
          <th>Distance (K)</th>
          <?php if( $this->is_public('show_activity_time')) : ?>
          <th>Time</th>
          <?php endif; ?>
          <?php if( $this->is_public('show_activity_pace')) : ?>
          <th>Pace (min/km)</th>
          <?php endif; ?>
          <?php if( $this->is_public('show_remarks')) : ?>
          <th>Remarks</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $this->activities( false ) as $row ) : ?>
        <tr>
          <td><?php echo $row->activity_name; ?></td>
          <td><?php echo $row->location; ?></td>
          <td><?php echo ucfirst($row->activity_type); ?></td>
          <td><?php echo date('F d, Y',strtotime($row->activity_date)); ?></td>          
          <td><?php echo $row->distance; ?></td>
          <?php if( $this->is_public('show_activity_time')) : ?>
          <td><?php echo $row->total_time; ?></td>
          <?php endif; ?>
          <?php if( $this->is_public('show_activity_pace')) : ?>
          <td><?php echo date('i:s',strtotime( $row->average_pace )); ?></td>
          <?php endif; ?>
          <?php if( $this->is_public('show_remarks')) : ?>
          <td class="nowrap"><?php echo $row->notes; ?></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>        
      </tbody>
    </table>
  </div>
  <div class="actions">
    <div id="btn_all_activities" class="ui teal button">Close</div>
  </div>
</div>

<div id="confirm-delete" class="ui small modal">
  <div id="modal-header" class="header">Confirmation</div>
  <div id="modal-content" class="content">
    <p>Are you sure you want to delete <strong><span id="delete-msg"></span></strong> record?</p>
  </div>
  <div class="actions">
    <div id="btn-modal-cancel" class="ui cancel button">Cancel</div>   
    <button id="btn-modal-delete" class="ui approve green left icon button" value=""><i class="delete icon"></i> Delete</button>
  </div>
</div>

<?php endif; ?>
