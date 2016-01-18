<div id="page" class="ui two column top aligned very relaxed stackable grid container">
 
    <div class="four wide right aligned column">
      <div class="ui secondary vertical pointing green menu">
        <a href="<?php echo home_url( 'home' ); ?>" class="item"> Home</a>
        <a href="<?php echo home_url( 'home/mygroups' ); ?>" class="item"> Groups</a>
        <a class="active item"> Activities</a>
        <a href="<?php echo home_url( 'home/connections' ); ?>" class="item"> Connections</a>
        <a href="<?php echo home_url( 'home/events-joined' ); ?>" class="item"> Events Joined</a>
      </div>
    </div>

     <div class="twelve wide column">

      <?php if ( isset( $result ) ) : 
        
          echo $result;
      
      endif; ?>        
        
        <div class="ui divided items"> 

          <div class="ui right floated buttons">
            <button id="btn_new_activity" class="ui green button">Add New Activity</button>
          </div>

          <h2 class="ui left aligned header">
            <i class="heartbeat icon"></i>
              <div class="content">
                My Running Activities
              <div class="sub header"> Records of all your training and races joined since you started running.</div>
            </div>
          </h2>

          
            <div class="ui green statistics">
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
                  Run Made
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
                  Farthest
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  <?php echo date('i:s',strtotime( $this->fastest_pace() )); ?>
                </div>
                <div class="label">
                  Fastest Pace (min/km)
                </div>
              </div>
            </div>
          

          <?php              
            $activities = $this->activities( false );
            $total_activities = count($activities);
            $ctr = $total_activities;
          ?>
          <?php if ( $total_activities > 0 ) : ?>            
            <table class="ui stackable table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Activity</th>
                  <th>Type</th>
                  <th>Date</th>
                  <th>Dist (K)</th>
                  <th>Time</th>
                  <th>Pace (min/km)</th>
                  <th><i class="setting icon"></i></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ( $activities as $row ) : ?>
                <tr>
                  <td><?php echo $ctr; ?></td>
                  <td><?php echo $row->activity_name; ?></td>
                  <td><?php echo ucfirst($row->activity_type); ?></td>
                  <td><?php echo date('F d, Y',strtotime($row->activity_date)); ?></td>          
                  <td><?php echo $row->distance; ?></td>
                  <td><?php echo $row->total_time; ?></td>
                  <td><?php echo date('i:s',strtotime( $row->average_pace )); ?></td>
                  <td>
                    <div class="ui mini buttons">
                      <button id="btn-update" class="ui icon button btn-update-<?php echo $row->activity_id;?>" value="<?php echo $row->activity_id; ?>"><i class="edit icon"></i></button>
                      <button id="btn-delete" class="ui icon button btn-delete-<?php echo $row->activity_id;?>" value="<?php echo $row->activity_id; ?>"><i class="delete icon"></i></button>
                    </div>
                  </td>
                </tr>
              <?php 
                $ctr--;
              endforeach; ?>        
              </tbody>
            </table>
          <?php else: ?>

          <div class="ui info message">
            <i class="close icon"></i>
            <div class="header">
              You have activity added yet. You may now start adding one.
            </div>
          </div>
        
        <?php endif; ?>
      </div> <!-- items -->
    </div> <!-- column 2 -->

</div> <!-- page -->

<div id="activity" class="ui modal">
  <div id="modal-header-activity" class="header">
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
                for($i = 0; $i <= 24; $i++) {
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
        <input type="hidden" name="activity[activity_id]" value="" id="activity_id" />      
        <button type="submit" class="ui teal positive right labeled icon right floated button">Save Activity <i class="checkmark icon"></i></button>
        <button id="btn_cancel" type="button" class="ui right floated default button">Cancel</button>
      
      <div class="ui hidden divider"></div>
      <div class="ui hidden divider"></div>
    </form>
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
