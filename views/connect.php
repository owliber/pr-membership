<?php 

/**
 * @file connect.php
 * @author owliber@yahoo.com
 * @date 2015-12-06
 * @version 1.0
 *
 */

?>

<div id="page" class="ui top aligned very relaxed stackable grid container">
	<div class="ui four doubling fluid link special cards">

		<?php 
			
			foreach ( $this->list_members() as $member ) : 
		
				$user = get_userdata( $member->ID );

				$thumb_file = $user->pr_member_thumbnail_image; //get_user_meta( $member->ID, 'pr_member_thumbnail_image', true );

				if ( empty( $thumb_file )) {
					$thumbnail = 'https://placeholdit.imgix.net/~text?txtsize=75&txt=thumbnail&w=800&h=800';
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
			<div class="content">
			  	<i class="right floated star icon"></i>
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
			      <?php echo wp_trim_words( $user->description, 15, ' ...'); ?>
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
</div>