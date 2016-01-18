jQuery(document).ready(function($) {
 
  $(".button#btn_join_group").on('click', function(){

    var id = $(this).val();

    var data = {
        action: 'join_group',
        security : AjaxJoin.security,
        group_id: id
      };

    $.post(AjaxJoin.ajaxurl, data, function(response) {

        if( response.result_code == 0) {
          
          if( response.group_privacy == 1 ) { // Private group
            $(".btn-join-"+response.group_id).text("Request Sent");
          } else {
            $(".btn-join-"+response.group_id).text("Joined");
            $(".member-total-"+response.group_id).text(response.member_total+" members");  
          }
          
          $(".btn-join-"+response.group_id).attr('disabled', 'disabled');

        } else {
          $(".error-"+response.group_id).addClass("ui error message");
          $(".error-"+response.group_id).text("Unable to join at this time. Please try again later.");
          $(".btn-join-"+response.group_id).attr('disabled', 'disabled');
        }

    }); //ajax post

  }); //btn_join_group

  $(".button#btn_join_event").on('click', function(){

    var id = $(this).val();

    var data = {
        action: 'join_event',
        security : AjaxJoin.security,
        event_id: id
      };

    $.post(AjaxJoin.ajaxurl, data, function(response) {

        if( response.result_code == 0) {
          $(".member-joined-"+response.event_id).text(response.member_joined+" Joined");
          $(".btn-join-event-"+response.event_id).text("Joined");          
          $(".btn-join-event-"+response.event_id).attr('disabled', 'disabled');
          //Huge button
          $(".btn-join-event-huge-"+response.event_id).text("Joined");
          $(".btn-join-event-huge-"+response.event_id).attr('disabled', 'disabled');
        } else {
          $("span.error-"+response.event_id).addClass("ui error message");
          $("span.error-"+response.event_id).text("Unable to join at this time.");
          $(".btn-join-event-"+response.event_id).attr('disabled', 'disabled');
          $(".btn-join-event-huge-"+response.event_id).attr('disabled', 'disabled');
        }

    }); //ajax post

  }); //btn_join_event

  $(".button#btn_join_event_huge").on('click', function(){

    var id = $(this).val();

    var data = {
        action: 'join_event',
        security : AjaxJoin.security,
        event_id: id
      };

    $.post(AjaxJoin.ajaxurl, data, function(response) {

        if( response.result_code == 0) {
          $(".member-joined-"+response.event_id).text(response.member_joined+" Joined");
          //Below Huge button
          $(".btn-join-event-huge-"+response.event_id).text("Joined");
          $(".btn-join-event-huge-"+response.event_id).attr('disabled', 'disabled');

          //Top Button
          $(".btn-join-event-"+response.event_id).text("Joined");          
          $(".btn-join-event-"+response.event_id).attr('disabled', 'disabled');
          
        } else {
          $("span.error-"+response.event_id).addClass("ui error message");
          $("span.error-"+response.event_id).text("Unable to join at this time.");
          $(".btn-join-event-"+response.event_id).attr('disabled', 'disabled');
          $(".btn-join-event-huge-"+response.event_id).attr('disabled', 'disabled');
        }

    }); //ajax post

  }); //btn_join_event_huge

  $(".button#btn_join_archive_event").on('click', function(){

    var id = $(this).val();

    var data = {
        action: 'join_event',
        security : AjaxJoin.security,
        event_id: id
      };

    $.post(AjaxJoin.ajaxurl, data, function(response) {

        if( response.result_code == 0) {
          $(".member-joined-archive-"+response.event_id).text(response.member_joined+" Joined");
          $(".btn-join-archive-event-"+response.event_id).text("Joined");          
          $(".btn-join-archive-event-"+response.event_id).attr('disabled', 'disabled');
          
        } else {
          $("span.error-archive-"+response.event_id).addClass("ui error message");
          $("span.error-archive-"+response.event_id).text("Unable to join at this time.");
          $(".btn-join-archive-event-"+response.event_id).attr('disabled', 'disabled');
        }

    }); //ajax post

  }); //btn_join_event_huge

  $(".button#btn_subscribe").on('click', function(){
    $('#modal-subscribe.ui.modal')
      .modal('setting', 'closable', true)
      .modal('setting', 'transition', 'fade')
      .modal({
         blurring: true,

      })
      .modal('show')
  });


  // Submit subscription
  $(".button#subscribe").on('click', function(){

      var name, email, emailreg;
      var emailreg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      var emailblockreg = /^([\w-\.]+@(?!yopmail.com)(?!mailinator.com)(?!guerrillamail.com)(?!10minutemail.com)(?!20minutemail.com)(?!fakeinbox.com)(?!meltmail.com)(?!trashmail.com)([\w-]+\.)+[\w-]{2,4})?$/;

      subscriber_name = $("#subscriber_name").val();
      subscriber_email = $("#subscriber_email").val();

      var data = {
          action: 'subscribe_to_newsletter',
          security : AjaxJoin.subscribe,
          name: subscriber_name,
          email: subscriber_email
      };

      if( subscriber_name == "" )
        alert('Please enter your name');
      else if( ! emailreg.test( subscriber_email ))
        alert('The email address is invalid. Please check again.');
      else if( ! emailblockreg.test( subscriber_email ))
        alert('Disposable email addresses are not allowed. Please use yahoo, hotmail or gmail.');
      else {

          $.post(AjaxJoin.ajaxurl, data, function(response) {

            $("#subscribe-form").submit();
            $('#modal-subscribe.ui.modal').modal("hide");
            //alert(response.result_msg);

        }); //ajax post      

      }
  });

}); /* document ready */