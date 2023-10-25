$(document).ready(function() {

  // ******************************* REGISTRATION FORM  - RESET THE FORM **************************************
  $('#buttonResetRegister').on('click', function() {
    $('#usernameRegister').val('');
    $('#emailRegister').val('');
    $('#passwordRegister').val('');
    $('#passwordConfirmRegister').val('');
    $('#avatarUploadRegister').val('');

    // Remove field feedback classes
    $('#usernameRegister').removeClass('error-field success-field');
    $('#emailRegister').removeClass('error-field success-field');
    $('#passwordRegister').removeClass('error-field success-field');
    $('#passwordConfirmRegister').removeClass('error-field success-field');
    $('#avatarUploadRegister').removeClass('error-field success-field');
  });

  // Dont exit the modal if there is any error
     if (showErrorModal) {  // I initialize that variable before the modal in the index.php
        $('#registerModal').modal('show');
    }

    // Fade out for successMessage      
    setTimeout(function() {
      $('#successMessage').fadeOut('fast');
    }, 1000); // Time in milliseconds
    
  document.getElementById('buttonResetRegister').addEventListener('click', function() {
    var errorFields = ['errorUsername', 'errorEmail', 'errorPassword', 'errorConfirmPassword', 'errorAvatar'];

    errorFields.forEach(function(field) {
        var elements = document.getElementsByClassName(field);
        
        Array.from(elements).forEach(function(element) {
            element.classList.remove(field);
        });
    });
  });
});
