<?php

/*
 * See http://www.imavex.com/pfbc3.x-php5/#getting-started
 * http://www.imavex.com/php-form-builder-class/documentation/index.php
 */


//----------AFTER THE FORM HAS BEEN SUBMITTED----------


/*
 * My Form
 * 
 * 
 * Extends the PHP Form Class to add validation methods
 * 
 */

class MyForm extends DowncastForm {

        /**
     * Validate Email
     *
     * Validate Email
     * ref: http://stackoverflow.com/q/3722831/3306354
     * @param $email
     * @return void
     */
    public function validateSomething( $text ) {

        /*
         * Return True If Optional and Trim
         * 
         * This allows you to make this field optional
         * but still able to apply this rule when something is 
         * submitted.

         */
        if ( is_null( trim( $email ) ) ) {
            return true;
}


        $validation_result = false;


        return $validation_result;

    }
    
}


$form = new MyForm(
        'login'  // form id , arbitrary string that is unique for the form
);

$form->prevent =array("bootstrap", "jquery");


/*
 * Add Server Side Validation Rules
 */
$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateDomainName' ), //callback
        null, //paramaters
        null //error message
);

$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateRegex' ), //callback
       '/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/', //paramaters
        'Bad Domain as validated by regex' //error message
);

$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateMinLength' ), //callback
       2, //paramaters
        'Must be at least 2 characters long' //error message
);
$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateMaxLength' ), //callback
       20, //paramaters
        'Too long! Cant be more than 20 characters long' //error message
);
$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateList' ), //callback
       array('blue','red','yellow'), //paramaters
        "Must choose either 'blue','yellow',or 'red' " //error message

);

$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateList' ), //callback
       array('blue','red','yellow'), //paramaters
       'Must not be blue,red, or yellow', //error message
        true //applies the not operator. in this case, rule will pass if the submitted item is NOT on the list
);





/*
 * Apply Server Side Validation
 */

 $form->validateFormAfterSubmit(
         false  // Whether to clear values after successful client side validation
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