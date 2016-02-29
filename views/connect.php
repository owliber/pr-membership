<?php 

/**
 * @file connect.php
 * @author owliber@yahoo.com
 * @date 2015-12-06
 * @version 1.0
 *
 */

?>

<div class="ui top aligned very relaxed stackable grid container">
	<div class="ui teal secondary pointing fluid menu">
	  <a href="<?php echo home_url( 'connect?view=featured' );?>" class="<?php echo $this->view == 'featured' ? 'active' : ''; ?> item">
	    Featured
	  </a>
	  <a href="<?php echo home_url( 'connect?view=all' );?>" class="<?php echo $this->view == 'all' || empty( $this->view ) ? 'active' : ''; ?> item">
	    All Runners
	  </a>	  
	  <!-- <a href="<?php echo home_url( 'connect?r=popular' );?>" class="item">
	    Popular
	  </a>
	  <a href="<?php echo home_url( 'connect?r=new' );?>" class="item">
	    New
	  </a> -->
	</div>	
	<?php if( wp_is_mobile() ) : ?>
		<div class="ui hidden divider"></div>
	<?php endif; ?>
	<div class="ui four fluid link special stackable cards">

		<?php 
			
			foreach ( $this->list_members() as $member ) : 
		
				$user = get_userdata( $member->ID );
				$is_featured = get_user_meta( $user->ID, 'is_featured', true );

				if( isset( $is_featured ) && $is_featured == 1 )
					$color = 'green';
				else
					$color = '';

				$thumb_file = $user->pr_member_thumbnail_image; //get_user_meta( $member->ID, 'pr_member_thumbnail_image', true );

				if ( empty( $thumb_file )) {
					//$thumbnail = 'https://placeholdit.imgix.net/~text?txtsize=75&txt=thumbnail&w=800&h=800';
					$thumbnail = THUMB_DIR . '/random/thumbnail_placeholder_'.rand(1,9).'.jpg';
				} else {
					$thumbnail = THUMB_DIR . '/'.$thumb_file;
				}
		?>

		<div class="card">				
			<div class="blurring dimmable image">
				<div class="ui dimmer">
					<div class="content">
						<div class="center">
				        	<a class="ui teal button" href="<?php echo home_url( 'member/'.$member->user_login ); ?>">View Profile</a>
				        </div>
					</div>
				</div>
				 <img src="<?php echo $thumbnail; ?>">
			</div>
			<?php if( $is_featured == 1 && $this->view != 'featured' ) : ?>
				<div class="ui green top left attached label">
			        Featured
			     </div>
		 	<?php endif; ?>
			<div class="content">
				
			  	<!-- <i class="right floated star icon"></i> -->
			    <a class="header">
			    <?php 
			    	
			    	if ( is_public( 'show_name', $user->ID ) && $user->first_name != "" && $user->last_name != "" )
			    		echo $user->first_name . ' ' . $user->last_name;
			    	else
			    		echo ucfirst( $user->display_name );
				?>
				</a>
			    <div class="meta">
			     <?php if ( is_public( 'show_name', $user->ID ) &&  $user->display_name != "" ) : ?>
			      <span class="text">
			      	<?php echo $user->display_name; ?>
			      </span>
				 <?php endif; ?>
			    </div>
			    <div class="description">
			      <?php echo wp_trim_words( $user->description, 15, ' &raquo;'); ?>
			    </div>
			</div>
			<div class="extra content">
				<?php 
					
					$year_started_running = $user->year_started_running;
					if ( null != $year_started_running ) :

				?>
			      <span class="right floated">
			         <i class="history icon"></i> <?php echo $year_started_running;  ?>
			      </span>
			    <?php endif; ?>
			    <?php 
		        	
		        	$total_connections = $user->total_connections;
		        	if ( null != $total_connections ) :
		        		$plural = $total_connections > 1 ? ' connections' : ' connection';

		        ?>
		      	<span class="connections">
		        	<i class="users icon"></i>
		        	<?php echo $total_connections . $plural; ?>
		      	</span>
		      <?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php if( wp_is_mobile() ) : ?>
		<div class="ui hidden divider"></div>
	<?php endif; ?>
</div>