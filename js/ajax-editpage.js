jQuery(document).ready(function($) {
 
  //Edit group modal   
   $(".button[name='colorpicker']").on('click', function(){

    var id = $(this).val();

    var data = {
        action: 'change_headline_bg',
        security : AjaxEditPage.security,
        user_id: id
      };

     $.post(AjaxEditPage.ajaxurl, data, function(response) {

          if ( response.result_code == 0 ) {
            alert("hello");

          } else {
            alert(response.result_msg);
          }

          
      }); /* post */

  }); /* onclick */

}); /* document ready */