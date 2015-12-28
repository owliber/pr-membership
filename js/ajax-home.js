jQuery(document).ready(function($) {

  $("#btn_accept.positive").click(function() {

    var id = $(this).val();

    var data = {
      action: 'process_request',
      security : AjaxHome.security,
      user_id: id,
      status : 1
    };

    if ( $("#user_id").val() == 0) {
      // if not logged in, redirect to registration page
      alert("Please log-in or sign-up to connect.\nYou will now be redirected to the sign-up and login page.");

    } else {

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      $.post(AjaxHome.ajaxurl, data, function(response) {

          if( response.result_code == 0 ) {
            $(".btn-accept-"+ response.result_id).text("Connected");
            $(".btn-accept-"+ response.result_id).addClass("disabled");
            $(".btn-ignore-"+ response.result_id).hide();
            $(".accepted-message-"+ response.result_id).text("Yey! Thank you. See you on the road! ");
          } else {
            $(".error-message-"+ response.result_id).text("Problem encountered. Try again later.");
            $(".btn-accept-"+ response.result_id).addClass("disabled");
            $(".btn-ignore-"+ response.result_id).addClass("disabled");
          }
          

      });

    }

  });

  //Ignore connection request
  $("#btn_ignore.negative").click(function() {

    var id = $(this).val();
    var data = {
      action: 'process_request',
      security : AjaxHome.security,
      user_id: id,
      status : 2
    };

    if ( $("#user_id").val() == 0) {
      // if not logged in, redirect to registration page
      alert("Please log-in or sign-up to connect.\nYou will now be redirected to the sign-up and login page.");

    } else {

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      $.post(AjaxHome.ajaxurl, data, function(response) {

          if( response.result_code == 0 ) {
            $(".btn-ignore-"+ response.result_id).text("Not Now");
            $(".btn-ignore-"+ response.result_id).addClass("disabled");
            $(".btn-accept-"+ response.result_id).hide();
            $(".ignored-message-"+ response.result_id).text("Sorry, I'd love to follow you :(");
          } else {
            $(".error-message-"+ response.result_id).text("Problem encountered. Try again later.");
            $(".btn-accept-"+ response.result_id).addClass("disabled");
            $(".btn-ignore-"+ response.result_id).addClass("disabled");
          }
          

      });

    }

  });


  //Acknowledge friend connection
  $("#btn_acknowledge.positive").click(function() {

    var id = $(this).val();
    
     var data = {
      action: 'process_request',
      security : AjaxHome.security,
      user_id: id,
      status : 3
    };

    if ( $("#user_id").val() == 0) {
      // if not logged in, redirect to registration page
      alert("Please log-in or sign-up to connect.\nYou will now be redirected to the sign-up and login page.");

    } else {

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      $.post(AjaxHome.ajaxurl, data, function(response) {

          if( response.result_code == 0 ) {
            $(".ack-message-"+ response.result_id).text("See you on the road!");
            $(".btn-ack-"+ response.result_id).addClass("disabled");
          } else {
            $(".btn-ack-"+ response.result_id).addClass("disabled");
            $(".error-message-"+ response.result_id).text("Problem encountered. Try again later.");
          }
          

      });

    }

  });

  // Accept member to group
  
   $("#btn_group_accept.positive").click(function() {

    var id = $(this).val();
    var gid = $("#group-id-"+id).val();

    var data = {
      action: 'process_group_request',
      security : AjaxHome.security,
      user_id: id,
      group_id: gid,
      status : 1
    };

    if ( $("#user_id").val() == 0) {
      // if not logged in, redirect to registration page
      alert("Please log-in or sign-up to join this group.\nYou will now be redirected to the sign-up and login page.");

    } else {

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      $.post(AjaxHome.ajaxurl, data, function(response) {

          if( response.result_code == 0 ) {
            $(".btn-group-accept-"+ response.result_id).text("Added To Group");
            $(".btn-group-accept-"+ response.result_id).addClass("disabled");
            $(".btn-group-ignore-"+ response.result_id).hide();
            $(".group-request-message-"+ response.result_id).text("A new member of the group is added.");
          } else {
            $(".group-error-message-"+ response.result_id).text("Problem encountered. Try again later.");
            $(".btn-group-accept-"+ response.result_id).addClass("disabled");
            $(".btn-group-ignore-"+ response.result_id).addClass("disabled");
          }
          

      });

    }

  });

  // Reject group join request
  
   $("#btn_group_ignore.negative").click(function() {

    var id = $(this).val();
    var gid = $("#group-id-"+id).val();

    var data = {
      action: 'process_group_request',
      security : AjaxHome.security,
      user_id: id,
      group_id: gid,
      status : 2
    };

    if ( $("#user_id").val() == 0) {
      // if not logged in, redirect to registration page
      alert("Please log-in or sign-up to join this group.\nYou will now be redirected to the sign-up and login page.");

    } else {

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      $.post(AjaxHome.ajaxurl, data, function(response) {

          if( response.result_code == 0 ) {
            $(".btn-group-ignore-"+ response.result_id).text("Declined");
            $(".btn-group-ignore-"+ response.result_id).addClass("disabled");
            $(".btn-group-accept-"+ response.result_id).addClass("disabled");
            $(".group-request-message-"+ response.result_id).text("You refused to add this member to the group.");
          } else {
            $(".group-error-message-"+ response.result_id).text("Problem encountered. Try again later.");
            $(".btn-group-accept-"+ response.result_id).addClass("disabled");
            $(".btn-group-ignore-"+ response.result_id).addClass("disabled");
          }
          

      });

    }

  });
});