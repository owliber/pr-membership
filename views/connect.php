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
					$thumbnail = 'http://placehold.it/800x800';
				} else {
					$thumbnail = THUMB_DIR . '/'.$thumb_file;
				}
		?>

		<div class="card">
			<div class="blurring dimmable image">
				<div class="ui dimmer">
					<div class="content">
						<div class="center">
				        	<a class="ui primary button" href="<?php echo home_url( 'member/'.$member->user_login ); ?>">View Profile</a>
				        </div>
					</div>
				</div>
				 <img src="<?php echo $thumbnail; ?>">
			</div>
			<div class="content">
			  	<i class="right floated star icon"></i>
			    <a class="header">
			    <?php 
			    	
			    	if ( $user->first_name != "" && $user->last_name != "" )
			    		echo $user->first_name . ' ' . $user->last_name;
			    	else
			    		echo ucfirst( $user->user_nicename );
				?>
				</a>
			    <div class="meta">
			      <span class="text">
			      	<?php 
			      	 
			      	 	if ( $user->display_name != "")
			      	 		echo $user->display_name;
			      	 	else 
			      	 		echo "&nbsp;";
			        ?>
			    </span>
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

		<!-- Dummy Cards -->

		<div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui inverted button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/elliot.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Team Fu</a>
		      <div class="meta">
		        <span class="date">Create in Sep 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>
		  <div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui inverted dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui primary button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/jenny.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Team Hess</a>
		      <div class="meta">
		        <span class="date">Create in Aug 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>

		  <div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui inverted button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/steve.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Steve Jobes</a>
		      <div class="meta">
		        <span class="date">Create in Sep 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>

		  <div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui inverted button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/daniel.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Daniel Louise</a>
		      <div class="meta">
		        <span class="date">Create in Sep 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>

		  <div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui inverted button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/helen.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Helen Troy</a>
		      <div class="meta">
		        <span class="date">Create in Sep 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>

		  <div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui inverted button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/jenny.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Jenny</a>
		      <div class="meta">
		        <span class="date">Create in Sep 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>

		  <div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui inverted button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/veronika.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Veronika</a>
		      <div class="meta">
		        <span class="date">Create in Sep 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>

		  <div class="card">
		    <div class="blurring dimmable image">
		      <div class="ui dimmer">
		        <div class="content">
		          <div class="center">
		            <div class="ui inverted button">Add Friend</div>
		          </div>
		        </div>
		      </div>
		      <img src="http://semantic-ui.com/images/avatar/large/stevie.jpg">
		    </div>
		    <div class="content">
		      <a class="header">Stevie</a>
		      <div class="meta">
		        <span class="date">Create in Sep 2014</span>
		      </div>
		    </div>
		    <div class="extra content">
		      <a>
		        <i class="users icon"></i>
		        2 Members
		      </a>
		    </div>
		  </div>

		  <div class="card">
		    <div class="image">
		      <img src="http://semantic-ui.com/images/avatar2/large/matthew.png">
		    </div>
		    <div class="content">
		      <div class="header">Matt Giampietro</div>
		      <div class="meta">
		        <a>Friends</a>
		      </div>
		      <div class="description">
		        Matthew is an interior designer living in New York.
		      </div>
		    </div>
		    <div class="extra content">
		      <span class="right floated">
		        Joined in 2013
		      </span>
		      <span>
		        <i class="user icon"></i>
		        75 Friends
		      </span>
		    </div>
		  </div>
		  <div class="card">
		    <div class="image">
		      <img src="http://semantic-ui.com/images/avatar2/large/molly.png">
		    </div>
		    <div class="content">
		      <div class="header">Molly</div>
		      <div class="meta">
		        <span class="date">Coworker</span>
		      </div>
		      <div class="description">
		        Molly is a personal assistant living in Paris.
		      </div>
		    </div>
		    <div class="extra content">
		      <span class="right floated">
		        Joined in 2011
		      </span>
		      <span>
		        <i class="user icon"></i>
		        35 Friends
		      </span>
		    </div>
		  </div>
		  <div class="card">
		    <div class="image">
		      <img src="http://semantic-ui.com/images/avatar2/large/elyse.png">
		    </div>
		    <div class="content">
		      <div class="header">Elyse</div>
		      <div class="meta">
		        <a>Coworker</a>
		      </div>
		      <div class="description">
		        Elyse is a copywriter working in New York.
		      </div>
		    </div>
		    <div class="extra content">
		      <span class="right floated">
		        Joined in 2014
		      </span>
		      <span>
		        <i class="user icon"></i>
		        151 Friends
		      </span>
		    </div>
		  </div>

		<!-- End Dummy Cards -->
	</div>
</div>