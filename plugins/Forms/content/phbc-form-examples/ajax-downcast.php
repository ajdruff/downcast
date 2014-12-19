<?php

/**
 * Form Example - Ajax Login Using Server Side Validation
 * 
 * This example demonstrates how a form can return a result from a PHP method via ajax. The method can be anything but here we simulate a user login. 
 * 
 * 
 * 
 * 

 * This example uses the 'canned' ajax form handling that the DowncastForm class provides by default. It provides its own ajax javascript handler, and provides options you can configure using the configureAjax method.

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


/*
 * Declare the form object with our Form's ID
 */

$form = new DowncastForm(
        "myform", // form id , arbitrary string that is unique for the form
        true //$ajax whether we want the form to use ajax. Setting it to true will default to ajaxDowncastResponseHandler callback .If you want a different handler, call the configure method to set ajaxCallback
);
?>

<?php

/*
 * Set ajax Options ( optional ) 
 * These options will only work with the default ajaxDowncastResponseHandler
 * See class Readme for examples of all available options

  $options = array(
  'response_target' => 'downcast_form_response', //the id of the DOM element that should display any messages
  'hide_on_success' => false, //hides the form on success
  'collapse_on_hide' => false, //completely removes all form html from page when form is hidden
  'success_message' => '<div class="alert alert-success"><a class="close" href="#" data-dismiss="alert">×</a>Thank you for your submission!</div>', //default success message  //the default success message if the form handler does not supply one.
  'error_message' => '<div class="alert alert-danger"><a class="close" href="#" data-dismiss="alert">×</a>Your submission was unable to be processed. Please try again later.</div>' //the default error message if the form handler does not supply one.
  );
  $form->setAjaxOptions( $options );
 */

$options = array(
    'hide_on_success' => true, //hides the form on success
    'collapse_on_hide' => false, //completely removes all form html from page when form is hidden);
    'reset_on_success' => false
);
$form->setAjaxOptions( $options );



/*
 * Add Server Side Validation Rules
 * 
 */
$form->setValidationRule(
        'Password', //field name to be validated
        array( $form, 'validateMaxLength' ), //callback
        5, //paramaters
        'Too long! Password can\'t be more than 5 characters long' //error message
);


/*
 * Add Form Response Div
 * (required)
 * If missing, response won't be seen
 */

$form->addElement( new Element_HTML( '<div id="downcast_form_response"><!--Successful Form Response Here--></div>' ) );

/*
 * Add form id
 * (required)
 * If missing, response won't process
 */
$form->addElement( new Element_Hidden( "form", $form->id() ) ); //required or wont work


/*
 * Form Handler
 * Optionally, provide a different form handler
 * Otherwise, it will use this same script when submitted
 * $form->setAttribute( 'action', '/form/examples/ajax/login-server-validation/' );
 */

$form->setAttribute( 'action', '/form/examples/ajax/login-server-validation/' );


/*
 * Add Form Elements
 */
$form->addElement( new Element_HTML( '<legend>Login</legend>' ) );
$form->addElement( new Element_Email( "Email Address:", "Email", array( "required" => 1 ) ) );
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



        
        
if ( !class_exists( 'MyLoginForm' ) ) {


    class MyLoginForm {

        /**
         * Ajax Form Handler
         *
         * The ajax form handler for this form.
         *
         * @param none
         * @return string A Json response
         */
        function ajaxFormHandler()
        {


            /*
             * All Form Handlers must return a json response with the following
             *  variables
             * required:
             *   $response[ 'success' ] //boolean, whether it was true or false
             *   $response[ 'form' ]=$_POST['form']; //the form's id so we can target multiple forms. 
             * optional:
             *  $response['success_message']//if not supplied, the form's configured success message will be used.
             *  $response['error_message'] //if not supplied, the form's configured default error message will be used
             * 
             * example:
             * 
             * function ajaxFormHandler () {
             * 
             * //Do something with the $_POST variables here
             * 
             * $response[ 'success' ]=true;
             * $response['success_message']='The form was successfully submitted';
             *  $response[ 'form' ]=$_POST['form'];//must be the same id that was submitted
             * echo json_encode($response);
             * }
             *
             * 
             */

            /*
             * your code to validate user
             * 
             */
            $response[ 'form' ] = $_POST[ 'form' ];

            $possible_results = array(
                true,
                false
            );
            $key = array_rand( $possible_results );

            $response[ 'success' ] = $possible_results[ $key ];

            //    $response[ 'success_message' ] = '<div class="alert alert-success"><a class="close" href="#" data-dismiss="alert">×</a>Login Successful!</div>';
            $response[ 'error_message' ] = '<div class="alert alert-danger"><a class="close" href="#" data-dismiss="alert">×</a>Sorry, wrong username or password, please try again</div>';         //this can also be handled using a custom validation message. see example.
            $response_json = json_encode( $response );
            echo $response_json;
        }


    }


    }
    
/*
 * Add Form Handler
 * Must be called after class with method is defined
 */

        $form->handleAjaxForm(
            array( 'MyLoginForm', 'ajaxFormHandler' )//callback
           );
    
?>

