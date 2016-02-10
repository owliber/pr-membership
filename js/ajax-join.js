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

}); /* document ready */