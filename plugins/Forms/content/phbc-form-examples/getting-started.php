<?php
/**
 * Form Example - Getting Started
 * 
 * 
 * Usage:
 * 
 * Enable the 'Forms' plugin
 * Map a url to this form by adding the following to the config() method of the Forms Plugin: 
 *  $this->downcast()->addPage( '/form/examples/getting-started/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started.php' );
 * 
 * @link:http://www.imavex.com/pfbc3.x-php5/index.php#getting-started
 * These examples are based on the examples provided by the PHP Form Builder Class project
 * They are modified slightly to take advantage of the DowncastForm class which extends the PFBC's functionality
 * to provide server side validation using the setValidationRule method
 *
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
 * Declare the form object with our Form's ID
 */
$form = new DowncastForm(
        'login'  // form id , arbitrary string that is unique for the form
);


/*
 * Add Server Side Validation Rules
 * 
 * For this example, no Server Side Validation Rules will be added
 * As long as it passes the client side validation, it will receive a 'thank you for your submission' message
 * 
 * For an example using validation rules see getting-started-ssvalidation.php
 */


?>

###Demo Form - Getting Started




<button type="button" class="btn btn-warning collapsed" data-toggle="collapse" data-target="#about">
About this Demo
</button>


* Basic form demonstrating simple validation
* Requires the DownCast Forms Plugin which uses the [PHP Form Builder Class](http://www.imavex.com/pfbc3.x-php5/index.php), a popular PHP forms framework
* This demo is based on th [PHP Form Builder Class 'Getting Started' Example](http://www.imavex.com/pfbc3.x-php5/index.php#getting-started) 
* To use:
    1. Install the Forms Plugin
    2.  place the following in the Forms plugin config() method : 
    ```

    $this->downcast()->addPage( '/form/examples/getting-started/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started.php' );

    ```
* Source located at `<?php echo $this->file_getRelativePath(__FILE__); ?>`   <?php 


?> 
{: id="about" class="collapse" }




<?php

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

$form->addElement( new Element_Email( "Email Address:", "Email", array( "required" => 1 ) ) );
$form->addElement( new Element_Password( "Password:", "Password", array(
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