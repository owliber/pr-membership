jQuery( document ).ready( function() {

	$('#user_login').attr( 'placeholder', 'Username' );
	$('p.login-username').replaceWith( '<div class="field"><div class="ui left icon input"><input name="log" value="" placeholder="Username" id="user_login" required="" type="text" /><i class="user icon"></i></div></div>' );
	
	$('#user_pass').attr( 'placeholder', 'Password' );
	$('p.login-password').replaceWith( '<div class="field"><div class="ui left icon input"><input name="pwd" value="" placeholder="Password" id="user_pass" required="" type="password" /><i class="lock icon"></i></div></div>' );

	$('p.login-submit').replaceWith( '<div class="field"><div class="ui submit"><input class="ui blue button" name="wp-submit" value="Login" id="wp-submit" type="submit" /></div></div>' );
	
	$('.ui.checkbox').checkbox();
	$('.ui.dropdown').dropdown();

	$('.message.close')
	  .on('click', function() {
	    $(this)
	      .closest('.message')
	      .transition('fade')
	  });

	setTimeout(function(){ $('.fade').fadeOut() }, 5000);

	var content = [
		  { title: 'Andorra' },
		  { title: 'United Arab Emirates' },
		  { title: 'Afghanistan' },
		  { title: 'Antigua' },
		  { title: 'Anguilla' },
		  { title: 'Albania' },
		  { title: 'Armenia' },
		  { title: 'Netherlands Antilles' },
		  { title: 'Angola' },
		  { title: 'Argentina' },
		  { title: 'American Samoa' },
		  { title: 'Austria' },
		  { title: 'Australia' },
		  { title: 'Aruba' },
		  { title: 'Aland Islands' },
		  { title: 'Azerbaijan' },
		  { title: 'Bosnia' },
		  { title: 'Barbados' },
		  { title: 'Bangladesh' },
		  { title: 'Belgium' },
		  { title: 'Burkina Faso' },
		  { title: 'Bulgaria' },
		  { title: 'Bahrain' },
		  { title: 'Burundi' }
		  // etc
		];

	$('.ui.search')
	  .search({
	    source: content
	  })
	;

	/* Modal Forms */

	$('#new_activity.ui.modal')
	  .modal('setting', 'closable', false)
	  .modal('setting', 'transition', 'fade')
	  .modal('attach events', '#btn_new_activity.button', 'show')
	  .modal('attach events', '#btn_cancel.button', 'hide')
	  .modal({
	  	 blurring: false,

	  });

	$('#all-activities.ui.long.modal')
	  .modal('setting', 'closable', true)
	  .modal('setting', 'transition', 'fade')
	  .modal('attach events', '#btn_viewall_activity', 'show')
	  .modal('attach events', '#btn_all_activities', 'hide')
	  .modal({
	  	 blurring: true,

	  });

	$('#manage_group.ui.modal')
	  .modal('setting', 'closable', false)
	  .modal('setting', 'transition', 'fade')
	  .modal('attach events', '#btn_new_group', 'show')
	  .modal('attach events', '#btn_cancel', 'hide')
	  .modal({
	  	 blurring: true,
	  	 onShow : function() {
	  	 	$("#btn_submit_group").text("Submit");
	        $("#modal_header").text("Add New Group");
	        $("#group_id").val("");
	        $("#group_name").val("");
	        $("#group_location").val("");
	        $("#group_desc").val("");
	  	 }
	  });

	/* End Modal Forms */


	//Compute the average pace in minutes/km
	$(".timepicker").change( function() {

		var distance, total_time, 
			time_part, hour, minute, 
			seconds, total_minutes, pace, 
			pace_minutes, pace_seconds, average_pace;

		distance = $("#distance").val();
		total_time = $("#total_time").val();
		time_part = total_time.split(":");
		hour = time_part[0];
		minute = time_part[1];
		seconds = time_part[2] / 60;
		total_minutes =  ( hour * 60 )  + parseFloat(minute) + parseFloat(seconds);
		pace = total_minutes / distance;
		pace_minutes = Math.floor(pace)
		pace_seconds = (( pace - pace_minutes ) * 60).toFixed(0);
		pace_seconds = ("0" + pace_seconds).slice(-2); //add leading zero
		pace_minutes = ("0" + pace_minutes).slice(-2); //add leading zero
		average_pace = pace_minutes + ":" + pace_seconds;
		
		$("#average_pace").val(average_pace)

	});


	// Edit page sidebar on top
	
	$('.ui.top.sidebar')
	  .sidebar('setting', 'dimPage', false)
	  //.sidebar('setting', 'closable', false)
	  .sidebar('setting', 'transition', 'overlay')
	  .sidebar('attach events', '#btn-edit-page', 'show')
	  //.sidebar('attach events', '#btn-edit-done', 'hide')
	;

	$( "body" ).removeClass( "pushable" );

	$("input[name='position']").change( function() {
		$("#headline_position").submit();
	});

	// Hover dim effect on connect page
	$('.special.cards .image').dimmer({
	  on: 'hover'
	});


} ); // ready()