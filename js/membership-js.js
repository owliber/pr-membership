jQuery( document ).ready( function($) {

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

	// Validate modal form
	$('.field#frm_activity form')
	  .form({
	    on: 'blur',
	    fields: {
	      empty: {
	        identifier  : 'empty',
	        rules: [
	          {
	            type   : 'empty',
	            prompt : 'Please enter a value'
	          }
	        ]
	      },
	      dropdown: {
	        identifier  : 'dropdown',
	        rules: [
	          {
	            type   : 'empty',
	            prompt : 'Please select a dropdown value'
	          }
	        ]
	      },
	      checkbox: {
	        identifier  : 'checkbox',
	        rules: [
	          {
	            type   : 'checked',
	            prompt : 'Please check the checkbox'
	          }
	        ]
	      }
	    }
	  })
	;

	//Compute the average pace in minutes/km
	$("#distance").change( function() {

		var distance, total_time, 
			time_part, hour, minute, 
			seconds, total_minute, total_minutes, pace, 
			pace_minutes, pace_seconds, average_pace;

		distance = $("#distance").val();
		total_hour = $("#total_hour").val();
		total_minute = $("#total_minute").val();
		total_minutes =  ( total_hour * 60 )  + parseFloat(total_minute);

		pace = total_minutes / distance;
		pace_minutes = Math.floor(pace)
		pace_seconds = (( pace - pace_minutes ) * 60).toFixed(0);
		pace_seconds = ("0" + pace_seconds).slice(-2); //add leading zero
		pace_minutes = ("0" + pace_minutes).slice(-2); //add leading zero
		average_pace = pace_minutes + ":" + pace_seconds;
		
		$("#average_pace").val(average_pace)

	});


	//Compute the average pace in minutes/km
	$("#total_minute").change( function() {

		var distance, total_time, 
			time_part, hour, minute, 
			seconds, total_minute, total_minutes, pace, 
			pace_minutes, pace_seconds, average_pace;

		distance = $("#distance").val();
		total_hour = $("#total_hour").val();
		total_minute = $("#total_minute").val();
		total_minutes =  ( total_hour * 60 )  + parseFloat(total_minute);

		pace = total_minutes / distance;
		pace_minutes = Math.floor(pace)
		pace_seconds = (( pace - pace_minutes ) * 60).toFixed(0);
		pace_seconds = ("0" + pace_seconds).slice(-2); //add leading zero
		pace_minutes = ("0" + pace_minutes).slice(-2); //add leading zero
		average_pace = pace_minutes + ":" + pace_seconds;
		
		$("#average_pace").val(average_pace)

	});

	//Compute the average pace in minutes/km
	$("#total_hour").change( function() {

		var distance, total_time, 
			time_part, hour, minute, 
			seconds, total_minute, total_minutes, pace, 
			pace_minutes, pace_seconds, average_pace;

		distance = $("#distance").val();
		total_hour = $("#total_hour").val();
		total_minute = $("#total_minute").val();
		total_minutes =  ( total_hour * 60 )  + parseFloat(total_minute);

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

 // 	if( window.location.href == '/') {
 		
	// 	// Hide attribution for video if not loaded
	// 	window.addEventListener('load', function() {
	// 	    var video = document.querySelector('#bgvid');	    

	// 	    function checkLoad() {
	// 	        if (video.readyState !== 4) {
	// 	            $(".attribution").hide()
	// 	        } 
	// 	    }

	// 	    checkLoad();
	// 	}, false);

	// }


} ); // ready()

