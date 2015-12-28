<div id="page" class="ui two column top aligned very relaxed stackable grid container">
 
    <div class="four wide right aligned column">
      <div class="ui secondary vertical pointing green menu">
        <a href="<?php echo home_url( 'home' ); ?>" class="item"> Home</a>
        <a class="active item"> Groups</a>
        <a href="<?php echo home_url( 'home/connections' ); ?>" class="item"> Connections</a>
        <a href="<?php echo home_url( 'home/events-joined' ); ?>" class="item"> Events Joined</a>
      </div>
    </div>

     <div class="twelve wide column">

      <?php if ( $attributes ) : ?>
        <div class="ui <?php echo $attributes['status_code']; ?> message">
          <i class="close icon"></i>
          <div class="header">
            <?php echo $attributes['status_msg']; ?>
          </div>
        </div>
      <?php endif; ?>        
        
        <div class="ui divided items"> 

          <div class="ui right floated buttons">
            <button id="btn_new_group" class="ui green button">Add New Group</button>
            <a href="<?php echo home_url( 'groups' ); ?>" id="btn_global_groups" class="ui button">Global Groups</a>
          </div>

          <h2 class="ui left aligned header">
            <i class="users icon"></i>
              <div class="content">
              Your Groups
              <div class="sub header"> Lists of your team or group affiliation with other runners.</div>
            </div>
          </h2>

          <?php 
              $post_per_page = 25;
              if ( count( $this->other_groups ) > 0 ) : 
                  $args = array(
                    'posts_per_page'   => $post_per_page, 
                    'post_type'        => 'groups',
                    'post_status'      => 'publish',
                    'include'          => $this->other_groups,
                  );
              else :
                $args = array(
                    'posts_per_page'   => $post_per_page,
                    'author'           => $this->user_id,  
                    'post_type'        => 'groups',
                    'post_status'      => 'publish',
                  );
              endif;

              $groups = get_posts( $args ); 

          ?>
          <?php if ( count( $groups ) > 0 ) : ?>            
          <?php foreach ( $groups as $group ) : ?>
          <div class="item">
            <a class="ui small image" href="<?php echo home_url( 'groups/' . $group->post_name ); ?>">
              <?php if ( has_post_thumbnail( $group->ID ) ) {                  
                  echo get_the_post_thumbnail( $group->ID );
              }else {
                
                echo '<img src="'.WP_CONTENT_URL.'/uploads/thumbnail/wireframe.png">';
              }
              ?>
            </a>
            <div class="content">
              <a class="header" href="<?php echo home_url( 'groups/' . $group->post_name ); ?>"><?php echo $group->post_title; ?></a>
              <div class="meta">
                <span class="is_private"><?php 
                $is_private = get_post_meta( $group->ID, '_is_private', true ); 
                if ($is_private == 1)
                  $group_privacy = "Private";
                else 
                  $group_privacy = "Public";

                echo $group_privacy;
                ?></span>
              </div>
              <div class="description">
                <p><?php echo wp_trim_words( $group->post_content, 30, ' ...'); ?></p>
              </div>
              <div class="extra">
                <span class="member_count">
                  <?php 

                    $total_members = get_post_meta( $group->ID, '_group_total', true ); 
                    if ( empty( $total_members ) ) $total_members = 0;
                    $plural = (count( $total_members ) > 1 ) ? ' members' : ' member';
                    echo $total_members . $plural;

                 ?></span>
                <?php if( $group->post_author == $this->user_id ) : ?>
                 <button name="btn_edit_group" id="btn_edit_group" class="ui teal right floated default mini button" value="<?php echo $group->ID; ?>">Edit Group</button>
                <?php endif; ?>

               <?php if ( ! $this->is_membership_approved( $group->ID )) : ?>
                  <button class="ui right floated default mini button" disabled>Pending for approval</button>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php else: ?>

          <div class="ui info message">
            <i class="close icon"></i>
            <div class="header">
              You have no groups yet. Please create or <a href="<php echo home_url( 'groups' ); ?>">join</a> our groups.
            </div>
          </div>
        
        <?php endif; ?>
      </div> <!-- items -->
    </div> <!-- column 2 -->

</div> <!-- page -->

<!-- Modal Form -->
<div id="manage_group" class="ui small modal">
  <div id="modal_header" class="header">
    Add New Group
  </div>
  <div class="content">
    <form id="frm_group" class="ui equal width form" method="post"  action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" enctype="multipart/form-data">
      <?php wp_nonce_field('form_group', 'manage_form_group'); ?>
      <div class="required field">
          <label>Group Name</label>
          <input id="group_name" name="group[group_name]" placeholder="e.g Pasig Runners" type="text" value="" required>
      </div>
      <div class="required field">
          <label>Location</label>
          <input id="group_location" name="group[location]" placeholder="e.g Pasig City" type="text" value="" required>
      </div>  
       <div class="required field">
        <label>Description</label>
        <textarea id="group_desc" name="group[description]" rows="2" placeholder="A brief description of your group." required></textarea>
      </div>
      <div class="field">
        <label> Group Logo / Cover Image</label>
          <input type="file" id="group_logo" name="group_logo">
      </div>
      <div class="inline field">
        <div class="ui toggle checkbox">
          <input class="hidden" id="group_is_private" tabindex="3" type="checkbox" name="group[is_private]" value="1" >
          <label>Make this group private &mdash; <em>Private groups requires admins to authorize joins.</em> </label>
        </div>
      </div>
     
     <!-- Buttons -->
      <input type="hidden" name="group[group_id]" id="group_id" value="">
      <div id="btn_cancel" class="ui button">Cancel</div>
      <button id="btn_submit_group" type="submit" class="ui teal positive right button">Submit </button>

    </form>
  </div>
</div>


