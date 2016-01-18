jQuery(document).ready(function($) {

  function curDate(){
    var fullDate = new Date()
    //console.log(fullDate);
    //Thu May 19 2011 17:25:38 GMT+1000 {}
     
    //convert month to 2 digits
    var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
     
    //var currentDate = fullDate.getDate() + "/" + twoDigitMonth + "/" + fullDate.getFullYear();
    //19/05/2011
    var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" + fullDate.getDate();
    //2016-01-11
    //console.log(currentDate);
    return currentDate;
    
  }

  $("#btn_new_activity").on('click', function(){
      $('#activity.ui.modal')
        .modal('observeChanges', true)
        // .modal('attach events', '#btn_new_activity.button', 'show')
        .modal('attach events', '#btn_cancel.button', 'hide')
        .modal({
           closable : false,
           onVisible : function(){
              //Clear form
              $("#modal-header-activity").text("Add your new activity");
              $("#activity_id").val('');
              $("#activity_name").val('');      
              $("#activity_type").dropdown('set selected','Race');        
              $("#activity_date").val(curDate());
              $("#bibnumber").val('');
              $("#distance").val('0.0');
              $("#total_hour").dropdown('set selected','00');
              $("#total_minute").dropdown('set selected','00');
              $("#average_pace").val('00:00');
              $("#description").val('');
           },
            onApprove  : function() {
              $("#frm_activity").submit();
            }
        })
        .modal('show')
  });

  

  $(".button#btn-update").on('click', function(){

      var id = $(this).val();
      var data = {
        action: 'get_record_details',
        security : AjaxGetDetails.security,
        activity_id : id
      };

    $.post(AjaxGetDetails.ajaxurl, data, function(response) {
        $('#activity.ui.modal')
          .modal('attach events', '#btn_cancel.button', 'hide')
          .modal({
            onVisible : function(){            
              //Fill up form
              $("#modal-header-activity").text("Edit this activity");
              $("#activity_id").val(response.activity_id);
              $("#activity_name").val(response.activity_name);
              $('#activity_type').dropdown('set selected',response.activity_type);
              $("#activity_date").val(response.activity_date);
              $("#bibnumber").val(response.bibnumber);
              $("#distance").val(response.distance);
              $("#total_hour").dropdown('set selected',response.total_hour);
              $("#total_minute").dropdown('set selected',response.total_min);
              $("#average_pace").val(response.pace);
              $("#description").val(response.notes);
            },

          })
          .modal('show')
    });

  });

  $('#all-activities.ui.long.modal')
    .modal('setting', 'closable', true)
    .modal('setting', 'transition', 'fade')
    .modal('attach events', '#btn_viewall_activity', 'show')
    .modal('attach events', '#btn_all_activities', 'hide')
    .modal({
       blurring: true,

    });

  $("#activity_type").on('change', function(){
      
      var race_type = $("#activity_type").val();
      if( race_type == 'Training') {
        $("#bibnumber").val('');
        $("#bib").hide();
      } else {
        $("#bib").show();
      }

  });

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

  $(".button#btn-delete").on('click', function(){

    var id = $(this).val();

    var data = {
      action: 'get_record_details',
      security : AjaxGetDetails.security,
      activity_id : id
    };

    $.post(AjaxGetDetails.ajaxurl, data, function(response) {

      $("span#delete-msg").text(response.activity_name);
      $(".button#btn-modal-delete").val(response.activity_id);

      $('#confirm-delete.ui.small.modal')
        .modal('setting', 'transition', 'horizontal flip')
        .modal({
           closable  : false,
            onDeny    : function(){
              $(this).hide();
              window.location.href = location.href;
            },
            onApprove : function() {

                var delData = {
                  action: 'delete_record',
                  security : AjaxDelete.security,
                  activity_id : response.activity_id
                };
               
               //Delete record
               $.post(AjaxDelete.ajaxurl, delData, function(response) {
                  $("#modal-header").text("Delete Successful");
                  $("#modal-content").text(response.result_msg);
                  $("#btn-modal-delete").hide();
                  $("#btn-modal-cancel").text("Close");
                  $('#confirm-delete.ui.small.modal')
                    .modal('show');
               });
            }
        })
        .modal('show')
        
    });
    
  });

});