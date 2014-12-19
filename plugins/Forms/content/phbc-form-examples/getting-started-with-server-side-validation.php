<?php
/**
 * Form Example - Getting Started With Server Side Validation
 * 
 * 

 * These examples are based on the examples provided by the PHP Form Builder Class project
 * They are modified slightly to take advantage of the DowncastForm class which extends the PFBC's functionality
 * to provide server side validation using the setValidationRule method
 * @link:http://www.imavex.com/pfbc3.x-php5/index.php#getting-started
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @filesource
 */

/*
 * Add a 'src' tag via the ViewSource plugin
 * see / for more info {VIEW_SOURCE}
 */

?>{VIEW_SOURCE}<?php

/*
 * See http://www.imavex.com/pfbc3.x-php5/examples/form-elements.php
 * 
 * http://www.imavex.com/php-form-builder-class/documentation/index.php
 */

//----------AFTER THE FORM HAS BEEN SUBMITTED----------



/*
 * If you need a custom rule, 
 * see getting-started-with-custom-server-side-rule.php
 */
$form = new DowncastForm(
        'login'  // form id , arbitrary string that is unique for the form
);


?>

###Demo Form - Getting Started With Server Side Validation




<button type="button" class="btn btn-warning collapsed" data-toggle="collapse" data-target="#about">
About this Demo
</button>


* Basic form + Server Side Validation Rules
* Requires the DownCast Forms Plugin which uses the [PHP Form Builder Class](http://www.imavex.com/pfbc3.x-php5/index.php), a popular PHP forms framework
* This demo is based on the [PHP Form Builder Class 'Getting Started' Example](http://www.imavex.com/pfbc3.x-php5/index.php#getting-started) but uses validation extensions added by the DowncastForm class.
* To use:
    1. Install the Forms Plugin
    2.  place the following in the Forms plugin config() method : 
    ```

            $this->downcast()->addPage( '/form/examples/getting-started-with-validation/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started-with-server-side-validation.php' );

    ```
    3\. browse to the [form](/form/examples/getting-started-with-validation/)
* Source located at `<?php echo $this->file_getRelativePath(__FILE__); ?>`
{: id="about" class="collapse" }

<?php


 /*
  * Add Server Side Validation Rules
  * 
  */

/*
 * More Examples  - Please see class 'DowncastForm' for full list of built-in validation rules.


 */
$form->setValidationRule(
        'Email', //field name to be validated
        array( $form, 'validateEmail' ), //callback
        null, //paramaters
        'Please enter a valid email address' //error message
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
        'Email', //field name to be validated
        array( $form, 'validateList' ), //callback
        array( 'user@example.com', 'user1@example.com', 'user2@example.com' ), //paramaters
        "Must be user@example.com,user1@example.com, or user2@example.com" //error message
);

$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateList' ), //callback
        array( 'password', '123', 'admin' ), //paramaters
        "Must not be 'password', '123', 'admin'", //error message
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

if ( $form->isValidAfterSubmit() ){

    Form::clearValues( $form->id() ); //clear values if submission is successful
        echo '<div class="alert alert-success">Thank you for your submission.</div>';

}



$form->addElement( new Element_HTML( '<legend>Login</legend>' ) );
$form->addElement( new Element_Hidden( "form", $form_id ) );
/*
 * Form Handler
 * Optionally, provide a different form handler
 * Otherwise, it will use this same script when submitted
 * $form->setAttribute( 'action', '/your/form-handler/url/here/' );
 */
 
$form->addElement(new Element_Email("Email Address:", "Email", array("required" => 1)));
$form->addElement( new Element_Password( "Password:", "Password", array(
    "required" => 1
) ) );
$form->setAttribute( 'action', '/form/examples/getting-started-with-validation/' );
$form->addElement( new Element_Checkbox( "", "Remember", array(
    "1" => "Remember me"
) ) );
$form->addElement( new Element_Button( "Login" ) );
$form->addElement( new Element_Button( "Cancel", "button", array(
    "onclick" => "history.go(-1);"
) ) );
$form->render();

?>