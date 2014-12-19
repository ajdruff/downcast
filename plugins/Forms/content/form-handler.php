<?php



if(Form::isValid("login")) {
    /*The form's submitted data has been validated.  Your script can now proceed with any 
    further processing required.*/
    echo '<pre>', print_r( $_POST, true ), '</pre>';
    echo 'Thank you for your submission!';
}
else {
 
    /*Validation errors have been found.  We now need to redirect back to the 
    script where your form exists so the errors can be corrected and the form
    re-submitted.*/

    header ('Location: /my-test-form/');

     
}




?>