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
     * Configure
     *
     * Plugin Configuration
     * Add any code here to set variables and configuration values.
     *
     * @param none
     * @return void
     */
    public function config() {

        /*
         * Map Example Files to Urls
         */
        $this->downcast()->addPage( '/form/examples/getting-started/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started.php' );
        $this->downcast()->addPage( '/form/examples/getting-started-with-validation/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started-with-server-side-validation.php' );
        $this->downcast()->addPage( '/form/examples/getting-started-with-custom-rule/', dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started-with-custom-server-side-rule.php' );
$this->downcast()->addPage( '/form/examples/form-elements/', dirname( __FILE__ ) . '/content/phbc-form-examples/form-elements.php' );
$this->downcast()->addPage( '/form/examples/ajax/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax.php' );
$this->downcast()->addPage( '/form/examples/ajax/handler/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-form-handler.php' );
$this->downcast()->addPage( '/form/examples/ajax/login/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login.php' );
 $this->downcast()->addPage( '/form/examples/ajax/login-server-validation/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-downcast.php' );

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




}

?>
