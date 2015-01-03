<?php

/*
 * Forms DownCast Plugin
 * 
 * 
 * This plugin will not parse the main content of the page, but will parse those tags in the 'Exception' section in config();
 * 
 */

class Forms extends DowncastPlugin {

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function getAjaxActions() {
        return $this->_ajax_actions;
        }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function addAjaxAction( $action_name, $callback ) {

        if ( !isset( $this->_ajax_actions[ $action_name ] ) ) {
            $this->_ajax_actions[ $action_name ] = $callback;

}
    }

    private $_ajax_actions = null;

    /**
     * Configure
     *
     * Plugin Configuration
     * Add any code here to set variables and configuration values.
     *
     * @param none
     * @return void
     */
    public function config() {
    
        if (false){
        /*
         * Map Ajax Actions
         */
        $this->addAjaxAction(
                'MyAction', //action name submitted by form
                array( $this, 'MyOtherAction' ) //method to call when action is triggered
        );
        }
        /*
         * Map Example Files to Urls
         */
        $this->downcast()->addPage( '/ajax/handler/', dirname( __FILE__ ) . '/content/ajax.php' );

        $this->downcast()->addPage( '/my/first/form/', dirname( __FILE__ ) . '/content/my-first-form.php' );
        $this->downcast()->addPage( '/my/first/ajax/form/', dirname( __FILE__ ) . '/content/my-first-ajax-form.php' );

        $this->downcast()->addPage( '/form/examples/getting-started/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started.php' );

        $this->downcast()->addPage( '/form/examples/getting-started-with-validation/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started-with-server-side-validation.php' );
        $this->downcast()->addPage( '/form/examples/getting-started-with-custom-rule/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started-with-custom-server-side-rule.php' );
        $this->downcast()->addPage( '/form/examples/form-elements/', dirname( __FILE__ ) . '/content/phbc-form-examples/form-elements.php' );
        $this->downcast()->addPage( '/form/examples/ajax/geocode/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-geocode.php' );
        $this->downcast()->addPage( '/form/examples/ajax/login/downcast/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login-downcast.php' );
        $this->downcast()->addPage( '/form/examples/ajax/login/custom/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login-custom.php' );

//  $this->downcast()->addPage( '/form-handler/', dirname( __FILE__ ) . '/content/' . 'form-handler.php' );
        // $this->downcast()->addPage( '/my-test-form/test/', dirname( __FILE__ ) . '/content/' . 'form-handler.php' );


    }

    /**
     * Inititialize
     *
     * Plugin Initialization
     * Add any code here that you want fired when you create plugin and just after configuration.
     *
     * @param none
     * @return void
     */
    public function init() {
        session_start();
        if ( empty( $_POST ) ){

            unset( $_SESSION[ 'pfbc' ] );

            session_start();

}

        include(dirname( __FILE__ ) . "/PFBC/Form.php");
        include("DowncastForm.php");

    }

    /**
     * An Example Ajax Action
     *
     *
     * @param none
     * @return void
     */
    public function formAjaxActionMyAction() {
        /**
         * All Form Handlers must return a json response with the following
         *  variables
         * required:
         *   $response[ 'success' ] //boolean, true for success, false for failure
         *   $response[ 'form' ]=$_POST['form']; //the form's id so we can target multiple forms. 
         * optional:
         *  $response['success_message']//if not supplied, the form's default success message will be used, configurable using $form->setAjaxOptions
         *  $response['error_message'] //if not supplied, the form's default error message will be used, configurable using $form->setAjaxOptions
         */
        $form = new DowncastForm();


        /*
         * Add validation rules
         * For examples of all available rules, see the validation section in the docs
         */
        $form->setValidationRule(
                'Password', //field name to be validated
                array( $form, 'validateMaxLength' ), //callback
                5, //paramaters
                'Too long! Password can\'t be more than 5 characters long' //error message
        );


        /*
         * add some code here to process $_POST variables
         * e.g.: validate user, return search results,etc.
         * when done, set $response['success'] to true or false 
         * depending on whether process was successful
         * 
         */


        /*
         * Apply Server Side Validation
         * This will automatically return errors to the form
         * if the submission violates any validation rules
         * 
         */
        $form->validateAjaxForm();

        /*
         * return correct json content type
         * //returning a json content type allows client to understand 
         * response as an object so it parses it correctly 
         * without having to parse it explicitly
         */


        $response[ 'success' ] = true;
//$response[ 'error_message' ] = 'Your html error response here';
        $response[ 'form' ] = $_POST[ 'form' ];
        $response_json = json_encode( $response );
        echo $response_json;
        exit();



    }

/**
 * Example Form Action Method
 *
 * Processes the form's input
 *
 * @param none
 * @return void
     */
    public function MyAction() {

$result['success']=true;
$result['success_message']='you triggered '.__FUNCTION__;
$result['form']=$_POST['form'];
echo json_encode($result);


    }
}


?>
