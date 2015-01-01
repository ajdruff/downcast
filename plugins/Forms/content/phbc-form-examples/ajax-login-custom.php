<?php
/**
 * Form Example - Ajax Login Custom (Non-Preferred)
 * 

 * This example expands on the examples provided by the 
 * PHP Form Builder Class project. 
 * For a more basic example , see the ajax-geocode.php form.
 * 
 * 
 * @link:/form/examples/ajax/
 * @link:http://www.imavex.com/pfbc3.x-php5/examples/ajax.php
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @filesource
 */

   

if ( !isset( $_POST[ "form" ] ) ) {
    ?>
{VIEW_SOURCE}
###Demo Form - Ajax Login Using a Custom Javascript Ajax Response Handler(Non-Preferred Method)




<button type="button" class="btn btn-warning collapsed" data-toggle="collapse" data-target="#about">
About this Demo
</button>



* **Ajax Login Form Using a Custom Javascript Ajax Response Handler**
* This example uses the PHBC classes with minimal abstraction
and embeds the javascript ajax response handler within the form.
Its intended to demonstrate how to get  ajax working 
using the PHBC classes, but its preferred that 
the 'Ajax Login Downcast' (ajax-login-downcast.php) example be used 
instead as a template for your form since it is more user friendly
 (for example it doesn't require using the `if ( !isset( $_POST[ "form" ])` 
statements) , and provides a standard javascript handler that can be 
configured within the form without editing the javascript.
 * Requires the DownCast Forms Plugin which uses the [PHP Form Builder Class](http://www.imavex.com/pfbc3.x-php5/index.php), a popular PHP forms framework
 * Based on th [PHP Form Builder Class 'Ajax' Example](http://www.imavex.com/pfbc3.x-php5/examples/ajax.php) 

 * To use:
     1. Install the Forms Plugin
     2.  place the following in the Forms plugin config() method : 
     ```
     $this->downcast()->addPage( '/form/examples/ajax/login/custom/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login-custom.php' );
     ```
 * Source located at `<?php echo $this->file_getRelativePath( __FILE__ ); ?>`
{: id="about" class="collapse" }
<?php 
    /*
     * Declare the form object with our Form's ID
     */
    $form = new DowncastForm(
            "ajax"  // form id , arbitrary string that is unique for the form
    );
    $form->configure( array(
        "ajax" => 1,
        "ajaxCallback" => "ajaxResponseHandler"
    ) );


    /*
     * Add form id
     * (required)
     * If missing, response won't process
     */
    $form->addElement( new Element_Hidden( "form", $form->id() ) ); //required or wont work

/*
 * Form Handler
 * Optionally, provide a different form handler
 * Otherwise, it will use this same script when submitted,
 * e.g.: $form->setAttribute( 'action', $_POST['REQUEST_URI'] ); //same url as this page
 *  or  $form->setAttribute( 'action', '/url/to/form/handler' );
 */


    $form->addElement( new Element_HTML( '<legend>Login</legend>' ) );





    $form->addElement( new Element_Email( "Email Address:", "Email", array( 'value' => "user@example.com", "required" => 1 ) ) );
    $form->addElement( new Element_Textbox( "Password:", "Password", array( 'value' => "1234",
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
            function ajaxResponseHandler(response) {

            /*
             * hide_on_success
                    * Hide the form, showing only the response
             **/
                     var hide_on_success = true;
                    /*
                    * collapse_on_hide
                             * If the form is hidden, remove it entirely
                             * This 'collapses' the form by removing the html structure completely
                             * from the page.
                            **/


                            var collapse_on_hide = false;
                     /*
                     * reset_on_success
                     * reset the form on success
                     * If yuou are going to show the form after success,
                     * setting this to true will replace its values
                     * with the values before it was submitted, usually blanks.
                     */
                            var reset_on_success = true;
                            
                            
                            var form_id = response.form;
                            var form = $("#" + form_id);
                            var response_target_id = 'downcast_response_target' + form_id;
                            var response_target = $("#" + response_target_id);
                    if (response_target.length === 0) {
            form.before('<div id="' + response_target_id + '"><!--Ajax Response Here --></div>');
                            response_target = $("#" + response_target_id);
                    }




                    var success_message = response.success_message;
                            var error_message = response.error_message;
                          
                            if (response.success) {

                    response_target.html(success_message);
                            if (reset_on_success) {
                    form[0].reset(); //resets form after submission 
                    }
           if (hide_on_success) {

                if (collapse_on_hide) {
                    form.hide();//this will keep the form in place, so the structure of the page won't shrink when it goes away. if you want it completely removed use '.css('display','none'); or .hide();
                }
                else {
                    form.css('visibility', 'hidden');

                }

            }


        }
        else {

            response_target.html(error_message);

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

        $response[ 'success_message' ] = '<div class="alert alert-success"><a class="close" href="#" data-dismiss="alert">×</a>Login Successful!</div>';
        $response[ 'error_message' ] = '<div class="alert alert-danger"><a class="close" href="#" data-dismiss="alert">×</a>Sorry, wrong username or password, please try again</div>';         //this can also be handled using a custom validation message. see example.
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
        ajaxFormHandler();

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

