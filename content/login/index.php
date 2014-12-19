<?php

/*
 * See http://www.imavex.com/pfbc3.x-php5/#getting-started
 * http://www.imavex.com/php-form-builder-class/documentation/index.php
 */


//----------AFTER THE FORM HAS BEEN SUBMITTED----------



$form = new DowncastForm(
        'login'  // form id , arbitrary string that is unique for the form
);

/*
 * Add Server Side Validation Rules
 */
$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateEmail' ), //callback
        null, //paramaters
        null //error message ;leave null for standard error message
);

/*
 * Apply Server Side Validation
 */

 $form->validateFormAfterSubmit(
         false  // Whether to clear values after succesful client side validation
         );
 
 /*
  * Finally, accept the submission
  */
 
if ( $form->isValidAfterSubmit()){
    
    Form::clearValues( $form->id() ); //clear values if submission is successful
    echo '<br> Thank you for your submission.';
    
}







$form->addElement( new Element_HTML( '<legend>Login</legend>' ) );
$form->addElement( new Element_Hidden( "form", $form_id ) );
$form->setAttribute( 'action', '/my-test-form/' );
//$form->addElement(new Element_Email("Email Address:", "Email", array("required" => 1)));
$form->addElement( new Element_Textbox( "Password:", "Password", array(
    "required" => 1
) ) );
$form->addElement( new Element_Checkbox( "", "Remember", array(
    "1" => "Remember me"
) ) );
$form->addElement( new Element_Button( "Login" ) );
$form->addElement( new Element_Button( "Cancel", "button", array(
    "onclick" => "history.go(-1);"
) ) );
$form->render();
?>