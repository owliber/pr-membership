<?php

/** 
 * Upload Form Template
 */

?>

<!-- Upload Profile Background Form-->

	<div class="ui horizontal transparent-1 segments">	
	    <div class="ui inverted grey segment">
	    	<form id="upload_profile_bg" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" enctype="multipart/form-data">
			<?php wp_nonce_field('upload_profile_form', 'upload_profile_form_submitted'); ?>
		      <div class="ui text"> Upload a background at least 1024x768 in size</div> 
		      <div class="ui action input">				              
		      	<input type="file" id="profile_image" name="profile_image">
		      	<button id="btn-upload-bg" class="ui teal right labeled icon button" value="<?php echo $this->user_id; ?>">
		        <i class="upload icon"></i> Upload</button>
		      </div>
		  	</form>
	    </div>
	    <div class="ui inverted grey segment">
	    	<form id="colorpicker" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
	    		<div class="ui text"> Pick your profile card color</div> 
		      	<div class="ui buttons">	      	
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
	    <div class="ui grey inverted segment">
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