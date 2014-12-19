<?php
/**
 * Form Example - Ajax Login
 * 
 * This example demonstrates how a form can return a result from a PHP method via ajax. The method can be anything but here we simulate a user login.
 * 
 * 
 * 
 * Usage:
 * 
 * Enable the 'Forms' plugin
 * Map a url to this form by adding the following to the config() method of the Forms Plugin: 
 *          $this->downcast()->addPage( '/form/examples/ajax/login/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login.php' );
 * 

 * This example expands on the examples provided by the PHP Form Builder Class project. For a more basic example , see the ajax.php form.

 * @link:/form/examples/ajax/
 * @link:http://www.imavex.com/pfbc3.x-php5/examples/ajax.php
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @filesource
 */
/*
 * check if ajax
 */
//http://stackoverflow.com/a/4301154




if ( !isset( $_POST[ "form" ] ) ) {
    /*
     * Declare the form object with our Form's ID
     */
    $form = new DowncastForm(
            "ajax"  // form id , arbitrary string that is unique for the form
    );
    $form->configure( array(
        "ajax" => 1,
        "ajaxCallback" => "ajaxClientSideServerResponseHandler"
    ) );




    $form->addElement( new Element_HTML( '<legend>Login</legend>' ) );
    $form->addElement( new Element_Hidden( "form", $form->id() ) );//required or wont work
    /*
     * Form Handler
     * Optionally, provide a different form handler
     * Otherwise, it will use this same script when submitted
     * $form->setAttribute( 'action', '/form/examples/ajax/login/' );
     */

    $form->setAttribute( 'action', '/form/examples/ajax/login/' ); 





    $form->addElement( new Element_Email( "Email Address:", "Email", array( "required" => 1 ) ) );
    $form->addElement( new Element_Password( "Password:", "Password", array(
        "validation" => new Validation_AlphaNumeric,
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
    ?><script type="text/javascript">
            function ajaxClientSideServerResponseHandler(response) {
                if (response.user_is_valid){
                console.log(response.user + ' is a valid user');
            }
            else {
               console.log(response.user + ' is not a valid user'); 
            }
            }
    </script><?php
}

//----------AFTER THE FORM HAS BEEN SUBMITTED----------
if ( isset( $_POST[ "form" ] ) ) {    
//if (true){
    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return string A Json response
     */
    function ajaxServerSideFormHandler( )
    {
        /*
         * your code to validate user
         */
        $response['user_is_valid']=true;
        $response['user']=$_POST[ "Email" ];
        $response_json = json_encode( $response );
        echo $response_json;
    }        

    if ( DowncastForm::isValid( $_POST[ "form" ] ) ) {
        /*
         * return correct json content type
         * //returning a json content type allows client to understand 
         * response as an object so it parses it correctly 
         * without having to parse it explicitly
         */
        header( "Content-type: application/json" ); 
        /*
         * Call server side handler
         */
       ajaxServerSideFormHandler();
    
    exit();
    } else
        /*
         * Request the form to send an error back in json
         * PHBC will also display the error without additional coding
         * 
         * To Test, add an alphanumeric rule to field 
         * and add an exclamation mark to the field
         * This will pass the following error to in json back from the server:
         * {"errors":["Error: Password must be alphanumeric (contain only numbers, letters, underscores, and\/or hyphens)."]}
         */
        DowncastForm::renderAjaxErrorResponse( $_POST[ "form" ] );
    exit();
}
?>

