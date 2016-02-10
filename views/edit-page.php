
<div class="ui <?php echo ! wp_is_mobile() ? 'horizontal' : ''; ?> transparent-1 stackable segments">	
    <div class="ui center aligned inverted grey compact segment">
    	<div class="ui centered tiny rounded spaced image">
    		Preview
	      	<img id="preview_image" src="<?php echo get_template_directory_uri(); ?>/assets/images/wireframe.png" height="50" />
	    </div>
    </div>
    <div class="ui center aligned inverted grey compact segment">
    	<form id="upload_profile_bg" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" enctype="multipart/form-data">
	      <div class="ui text"> Select a background at least 1024x768 pixels.</div> 
	      <div class="ui action fluid input">		
	      	<input type="file" id="profile_image" name="profile_image">
	      	<button id="btn-upload-bg" class="ui teal right labeled icon button" value="<?php echo $this->user_id; ?>">
	        <i class="upload icon"></i> Upload</button>
	      </div>		       
	  	</form>
    </div>
    
    <div class="ui center aligned inverted grey compact segment">
    	<form id="colorpicker" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
    		<div class="ui text"> Pick your profile card color</div> 
	      	<div class="ui mini buttons">	      	
	      	  <button name="colorpicker" value="red" class="ui red button">&nbsp;</button>
			  <button name="colorpicker" value="orange" class="ui orange button">&nbsp;</button>
			  <button name="colorpicker" value="yellow" class="ui yellow button">&nbsp;</button>
			  <button name="colorpicker" value="olive" class="ui olive button">&nbsp;</button>
			  <button name="colorpicker" value="green" class="ui green button">&nbsp;</button>
			  <button name="colorpicker" value="teal" class="ui teal button">&nbsp;</button>
			  <button name="colorpicker" value="blue" class="ui blue button">&nbsp;</button>
			  <button name="colorpicker" value="violet" class="ui violet button">&nbsp;</button>
			  <button name="colorpicker" value="purple" class="ui purple button">&nbsp;</button>
			  <button name="colorpicker" value="pink" class="ui pink button">&nbsp;</button>
			  <button name="colorpicker" value="brown" class="ui brown button">&nbsp;</button>
			  <button name="colorpicker" value="grey" class="ui grey button">&nbsp;</button>
			  <button name="colorpicker" value="black" class="ui black button">&nbsp;</button>
			</div>
		</form>
    </div>	   
    <div class="ui center aligned grey inverted compact segment">
    	<form id="headline_position" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">	    
    	<div class="ui text"> Position</div> 	
			<?php 
				$headline_position = $attributes['headline_position'];
				$headline_position == "left" ? $radio_left="checked" : $radio_left=""; 
				$headline_position == "right" ? $radio_right="checked" : $radio_right=""; 
			?>
			<!-- <div class="ui label"> Position</div> -->
			<div class="fields">				    
			    <div class="field">
			      <div class="ui slider checkbox">
			        <input name="position" type="radio" value="left" <?php echo $radio_left; ?>>
			        <label>Left</label>
			      </div>
			    </div>
			    <div class="field">
			      <div class="ui slider checkbox">
			        <input name="position" type="radio" value="right" <?php echo $radio_right; ?>>
			        <label>Right</label>
			      </div>
			    </div>				    
			</div>
		</form>
    </div>	   
</div>