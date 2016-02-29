jQuery(document).ready(function(e) {
 
  $("#upload_profile_bg").on('submit',(function(e) {

    if ( $("#profile_image").val() != "" ) {
      e.preventDefault();
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
             console.log(result.result_msg);
             window.location.reload();
          }
        })
    } else {
      alert('Please select a background image.');
      return false;
    }

  }));

      // Function to preview image after validation
      $(function() {
          $("#profile_image").change(function() {
              
              var file = this.files[0];
              var imagefile = file.type;
              var imagesize = file.size;
              var imagesizeMB = imagesize / 1024 / 1024;
              var match= ["image/jpeg","image/png","image/jpg"];
                           
              if( ! (( imagefile == match[0] ) || ( imagefile == match[1] ) || ( imagefile == match[2]) ) )
              {
                alert('Please select a valid image file. Only jpeg, jpg, svg or png are allowed.');
                $("#profile_image").val("");
                return false;
              } else if (imagesizeMB > 4 ) {
                alert('Image is too large, file size should not exceed 4MB.');
                $("#profile_image").val("");
                return false;
              } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                  $("#profile_image").css("color","green");
                  $('#preview_image').attr('src', e.target.result);
                  var img = new Image;
                  img.onload = function() {
                    if ( img.width < 1024 ) {
                      alert("Image is too small, please upload at least 1024x768 pixels. Your current image size is "+img.width+"x"+img.height);
                      $("#profile_image").val("");
                      return false;
                    } else if ( img.width > 3072 ) {
                      alert("Image is too large, maximum size allowed is 3072x2048 pixels. Your current image size is "+img.width+"x"+img.height);
                      $("#profile_image").val("");
                      return false;
                    }
                    
                  };

                  img.src = reader.result;
                };

                reader.readAsDataURL(this.files[0]);
              }
          });
      });

}); /* document ready */