jQuery(document).ready(function($) {
 

  // Request friend connection from profile page
  $("#btn_connect").on('click', function(){

    var id = $(this).val();
    var connect_btn_text;

    var data = {
      action: 'connect_request',
      security : AjaxConnect.security,
      member_id : id

    };


    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    $.post(AjaxConnect.ajaxurl, data, function(response) {

        if ( response.result_code == 4 ) window.location.replace('http://localhost:9000/register');

        if ( response.result_code == 0 ) {

          if ( response.privacy_setting == 1 )
              connect_btn_text = 'Request Sent';
          else
              connect_btn_text = 'Connected';

          $("#total_connections").text( response.total_connections );
          $("#btn_connect").text( connect_btn_text );
          $("#btn_connect").attr('disabled', 'disabled');
          $("#btn_connect" ).removeClass( "teal" ).addClass( "green" );  

        } else {
          
          alert( response.result_msg );
        }
        
    });

  }); // End - request friend connection

  // Toggle connect button to disabled if user has pending connection request
  if ( $("#request_status").val() == true ) {
      $("#btn_connect").text("Request Sent");
      $("#btn_connect").attr('disabled', 'disabled');
      $( "#btn_connect" ).removeClass( "teal" ).addClass( "green" );
  } else {
      $( "#btn_connect" ).removeClass( "red" ).addClass( "teal" );
  }

});