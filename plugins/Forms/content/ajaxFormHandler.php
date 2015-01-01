<?php

if ( !function_exists( 'MyLoginForm' ) ) {
    /*
      All Form Handlers must return a json response with the following
     *  variables
     * required:
     *   $response[ 'success' ] //boolean, whether it was true or false
     *   $response[ 'form' ]=$_POST['form']; //the form's id so we can target multiple forms. 
     * optional:
     *  $response['success_message']//if not supplied, the form's configured success message will be used.
     *  $response['error_message'] //if not supplied, the form's configured default error message will be used

     */

    function ajaxFormHandler()
    {


        /*
         * your code to validate user
         * 
         */


        $response[ 'success' ] = true;

        $response[ 'form' ] = $_POST[ 'form' ];
        $response_json = json_encode( $response );
        echo $response_json;
    }


    }
?>

