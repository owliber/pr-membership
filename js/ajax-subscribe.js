jQuery(document).ready(function() {
 
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
  $(".button#subscribe2").on('click', function(){

      var name, email, emailreg;
      var emailreg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      var emailblockreg = /^([\w-\.]+@(?!yopmail.com)(?!mailinator.com)(?!guerrillamail.com)(?!10minutemail.com)(?!20minutemail.com)(?!fakeinbox.com)(?!meltmail.com)(?!trashmail.com)([\w-]+\.)+[\w-]{2,4})?$/;

      subscriber_name = $("#subscriber_name").val();
      subscriber_email = $("#subscriber_email").val();

      var data = {
          action: 'subscribe_to_newsletter',
          security : AjaxSubscribe.security,
          name: subscriber_name,
          email: subscriber_email
      };

      if( subscriber_name == "" ) {
        alert('Please enter your name');
        return false;
      }
      
      if( ! emailreg.test( subscriber_email )) {
        alert('The email address is invalid. Please check again.');
        return false;
      }
      
      if( ! emailblockreg.test( subscriber_email ))
        alert('Disposable email addresses are not allowed. Please use yahoo, hotmail or gmail.');
      else {

          $.post(AjaxSubscribe.ajaxurl, data, function(response) {

            $("#subscribe-form").submit();

        }); //ajax post      

      }
  });

  $(".button#subscribe").on('click', function(){

      var name, email, emailreg;
      var emailreg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      var emailblockreg = /^([\w-\.]+@(?!yopmail.com)(?!mailinator.com)(?!guerrillamail.com)(?!10minutemail.com)(?!20minutemail.com)(?!fakeinbox.com)(?!meltmail.com)(?!trashmail.com)([\w-]+\.)+[\w-]{2,4})?$/;

      subscriber_name = $("#subscriber_name").val();
      subscriber_email = $("#subscriber_email").val();

      var data = {
          action: 'subscribe_to_newsletter',
          security : AjaxSubscribe.security,
          name: subscriber_name,
          email: subscriber_email
      };

      if( subscriber_name == "" ) {
        alert('Please enter your name');
        return false;
      }
      
      if( ! emailreg.test( subscriber_email )) {
        alert('The email address is invalid. Please check again.');
        return false;
      }
      
      if( ! emailblockreg.test( subscriber_email ))
        alert('Disposable email addresses are not allowed. Please use yahoo, hotmail or gmail.');
      else {

          $.post(AjaxSubscribe.ajaxurl, data, function(response) {

            $("#subscribe-form").submit();
            $('#modal-subscribe.ui.modal').modal("hide");
            //alert(response.result_msg);

        }); //ajax post      

      }
  });

}); /* document ready */