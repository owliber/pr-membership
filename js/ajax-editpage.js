jQuery(document).ready(function(e) {
 
  $("#upload_profile_bg").on('submit',(function(e) {

      e.preventDefault();
      if( $("#profile_image").val() == "") {
        alert('Please select a background image');
        return false;
      }

      $('#btn-upload-bg').addClass( 'loading' );
      var formdata = new FormData(this);
      formdata.append('action','upload_profile_bg');
      formdata.append('security',AjaxUpload.security);
      
      $.ajax({
          url: AjaxUpload.ajaxurl,
          type: 'post',
          data : formdata,
          contentType: false,       // The content type used when sending data to the server.
          cache: false,             // To unable request pages to be cached
          processData:false,  
          success: function( result ) {
             $('#btn-upload-bg').removeClass( 'loading' );
             window.location.reload();
          }
        })

  }));

      // Function to preview image after validation
      $(function() {
          $("#profile_image").change(function() {
              
              var file = this.files[0];
              var imagefile = file.type;
              var match= ["image/jpeg","image/png","image/jpg"];

              if( ! (( imagefile == match[0] ) || ( imagefile == match[1] ) || ( imagefile == match[2]) ) )
              {
                alert('Please select a valid image file. Only jpeg, jpg, svg or png are allowed.');
                $("#profile_image").val("");
                return false;
              }
              else
              {
                
                var reader = new FileReader();

                reader.onload = function (e) {

                  $('#preview_image').attr('src', e.target.result);
                  $("#profile_image").css("color","green");

                  var img = new Image;
                  var sizeMB = file.size / 1024 / 1024;
                  img.src = e.target.result;
                  img.onload = function() {
                     var width = this.width;
                     var height = this.height;

                     if( width < 1024 ) {
                        alert("Please upload at least 1024px by 768px. Your current image dimension is "+width+"x"+height+" pixels");
                        $("#profile_image").val("");
                        return false;
                     }

                     if( sizeMB > 6 ) {
                        alert("Image size is too large ("+sizeMB+"MB). Please upload only below 6MB.");
                        $("#profile_image").val("");
                        return false;
                     }

                  };

                };
                reader.readAsDataURL(this.files[0]);    

              }
          });
      });

}); /* document ready */