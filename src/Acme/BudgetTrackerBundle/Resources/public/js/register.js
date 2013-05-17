$(document).ready(function(){
   /* $(".btn").on('click', function(){
        alert ($("#fos_user_registration_form_username").length); 
    });  */

function checkEmail() {
    var email = $("#fos_user_registration_form_email").val();
    var $error = $("#email_error");
    if (email == "") {
        $error.html("No Email Address");
        return false;
    }
    else {
        $error.html("");
        return true;
    }
}

    $("input[type=submit]").on("click", function(){
        alert("Blaa");
    });
}); 