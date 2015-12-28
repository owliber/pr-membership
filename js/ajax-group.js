jQuery(document).ready(function($) {
 
  //Edit group modal   
   $(".button#btn_edit_group").on('click', function(){

    var id = $(this).val();

    var data = {
        action: 'get_group_details',
        security : AjaxGroup.security,
        group_id: id
      };

     $.post(AjaxGroup.ajaxurl, data, function(response) {

          if ( response.result_code == 0 ) {

            $("#modal_header").text("Edit Group");
            $("#group_id").val( response.result_data.group_id );
            $("#group_name").val( response.result_data.group_name );
            $("#group_location").val( response.result_data.group_location );
            $("#group_desc").val( response.result_data.group_description );

            if( response.result_data.group_is_private == 1) {
              $("#group_is_private").attr("checked", "checked")
            }

            $("#btn_submit_group").text("Update");

          } else {
            alert(response.result_msg);
          }

          $('#manage_group.ui.modal')
            .modal('setting', 'closable', false)
            .modal('setting', 'transition', 'fade')
            .modal('attach events', '#btn_cancel', 'hide')
            .modal({
               blurring: true,
               onHide : function() {
                  $("#btn_submit_group").text("Submit");
                  $("#modal_header").text("Add New Group");
                  $("#group_id").val("");
                  $("#group_name").val("");
                  $("#group_location").val("");
                  $("#group_desc").val("");
               }, 
               
            })
            .modal('show');           
          
          
      }); /* post */

  }); /* onclick */

}); /* document ready */