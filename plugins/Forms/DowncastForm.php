<?php

/*
 * DowncastForm
 * 
 * 
 * Extends the PHP Form Class to add validation methods
 * 
 */

class DowncastForm extends Form {
    /*  */

    public function __construct( $id = null, $ajax = null,$action=null ) {
        {//Set id and ajax defaults based on whether it was called by ajax
        
            if ( $this->downcast()->isAjax() && is_null( $id ) ){//if ajax, set via post value and whether ajax call
                /*
                 * check if form was submitted and set id to $_POST[ 'form' ] value if available
                 * this permits us to make a simple call within an ajax callback
                 * e.g.: $form=new DowncastForm(); without having to reference  $_POST['form']
                 */
                $id = (isset( $_POST[ 'form' ] )) ? $_POST[ 'form' ] : "pfbc";

                /* If $jax not explicitly set,
                 * set it  based on whether it was called by ajax
                 */
                $ajax = (is_null( $ajax )) ? true : $ajax;




} else {

                /*
                 * default $id to "pfbc" when not explicitly set 
                 * and when created within a non-ajax request
                 */
                $id = (is_null( $id )) ? "pfbc" : $id;

                /*
                 * default $ajax to false when not explicitly set 
                 * and when created within a non-ajax request
                 */
                $ajax = (is_null( $ajax )) ? false : $ajax;
}
        }

        parent::__construct( $id );
        $this->config();
        
        
        /*
         * Configure PHBC Form class
         */
        if ( $ajax ) {
            /*
             * Tell PHBC we want an ajax form
             * It will then add the appropriate jscript to handle submit
             */
            $this->configure( array(
                "ajax" => 1,
                "ajaxCallback" => "DowncastPlugins.Forms.Forms.ajaxResponseHandler"
            ) );

            /*
             * If an ajax form, set Ajax Options
             * Calling it with an empty array causes it to use defaults
             * If user calls it again, their new settings will overwrite the defaults
             */
    //        $this->setAjaxOptions( array() );
            
$this->setAjaxOptions( array('action'=>$action) );
                /*
         * sets the forms action attribute (form handler) to the ajax handler
         */    
            $this->setAttribute( 'action', '/ajax/handler/' );
            




            /*
             * set form action
             * if $action is not provided, set to 'action_warning' which will show an error message
             * if $action is provided, then use it
             */
            
            $action=(!is_null($action))?$action:'action_warning';
            /*
             * set a javascript variable for action so it can be added to the form
             */






}else {
    
       
    
        /*
         * sets the default handler to the form's own page
         */
        $this->setAttribute( 'action', $_SERVER[ 'REQUEST_URI' ] );

    
}
        /*
         * Add form id
         * (required)
         * If missing, response won't process
         */
        $this->addElement( new Element_Hidden( "form", $this->id() ) );




    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
        public function __construct_OLD( $id = null, $ajax = null ) {
        {//set $id and $ajax arguments per defaults
            if ( $this->downcast()->isAjax() && is_null( $id ) ){
                /*
                 * set id to post value if available
                 * this permits us to make a simple call within an ajax callback
                 * e.g.: $form=new DowncastForm(); without having to reference  $_POST['form']
                 */
                $id = (isset( $_POST[ 'form' ] )) ? $_POST[ 'form' ] : "pfbc";

                $ajax = (is_null( $ajax )) ? true : $ajax;




} else {

                /*
                 * default $id to "pfbc" when not explicitly set 
                 * and when created within a non-ajax request
                 */
                $id = (is_null( $id )) ? "pfbc" : $id;

                /*
                 * default $ajax to false when not explicitly set 
                 * and when created within a non-ajax request
                 */
                $ajax = (is_null( $ajax )) ? false : $ajax;
}
        }

        parent::__construct( $id );
        $this->config();
        if ( $ajax ) {
            /*
             * Tell PHBC we want an ajax form
             * It will then add the appropriate jscript to handle submit
             */
            $this->configure( array(
                "ajax" => 1,
                "ajaxCallback" => "DowncastPlugins.Forms.Forms.ajaxResponseHandler"
            ) );

            /*
             * If an ajax form, set Ajax Options
             * Calling it with an empty array causes it to use defaults
             * If user calls it again, it will overwrite
             */
            $this->setAjaxOptions( array() );
}
        /*
         * Add form id
         * (required)
         * If missing, response won't process
         */
        $this->addElement( new Element_Hidden( "form", $this->id() ) );


        /*
         * sets the default handler to the form's own page
         */
        $this->setAttribute( 'action', $_SERVER[ 'REQUEST_URI' ] );



    }
/*
     * Keeps a reference to the parent 
     * 
     * @type
     */

    private $_plugin = null;

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function plugin() {
        if ( is_null( $this->_plugin ) ) {
            $this->_plugin = $this->downcast()->getPlugin( 'Forms' );
}
        return $this->_plugin;


    }

    /**
     * downcast
     *
     * downcast object
     *
     * @param none
     * @return void
     */
    public function downcast() {
        if ( isset( Downcast::$instances[ 0 ] ) ) {
            return Downcast::$instances[ 0 ];
} else {
            return null;
}


    }

    /**
     * Configure
     *
     * Configure Form Object
     *
     * @param none
     * @return void
     */
    public function config() {

        $this->VALIDATION_FIELD_ERROR_MESSAGE = 'Invalid - please edit and resubmit'; //add this into a config() method




      }

    private $_validation_rules = null;

    /**
     * Add Validation Rules
     *
     * Add an array of validation rules to 
     *
     * @param string $field_name
     * @param mixed $callback Indicates the method to use to validate the field. Either the name of a built in rule in which a prefix 'validate' is added (case sensitive) or a custom callable method. e.g.: 'Email' would translate to $this->validateEmail. array($this,methodName) would translate to $this->methodName(). Built in rules will eventually be taken from a simpli forms addon module and so could also be extended.
     * @param array $params An associative array of paramaters required by the validating method
     * @param string $success_message The message to display to the user on successful validation
     * @param string $error_message The message to display to the user on unsuccessful validation
     * @param bool $not When true, Returns true if the validation rule fails. This is useful for doing the opposite of the validation rules
     * @return void
     */
    public function setValidationRule( $field_name, $callback, $params, $error_message, $not = false ) {

        /*
         * Add all paramaters to 
         */
        $rule = array(
            'callback' => $callback,
            'params' => $params,
            'error_message' => $error_message,
            'not' => $not
                )
        ;



        $this->_validation_rules[ $field_name ][] = $rule;



    }

    /**
     * Get Validation Rules
     *
     * Returns the validation rules array that is contained in $this->_validation_rules
     * 
     *
     * @param string $field_name The field name of the set of rules you want to retrieve. If null, will return all rules for all fields
     * @return void
     */
    public function getValidationRules( $field_name = null ) {
        $this->_validation_rules = (is_null( $this->_validation_rules )) ? array() : $this->_validation_rules;
        if ( is_null( $field_name ) ) {
            return $this->_validation_rules;
} elseif ( !isset( $this->_validation_rules[ $field_name ] ) ){

            return null;

} else{

            return $this->_validation_rules[ $field_name ];

}
     }

    /**
     * Validate Ajax Form
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function validateAjaxForm() {



        $this->validateFormAfterSubmit();


        if ( !$this->isValidAfterSubmit() ){



            $this->renderAjaxErrorResponse( $this->id() );


            exit();

        }

    }

    /**
     * Validate Form
     *
     * Validates a Form. Calls Exit if validation fails and responds back with errors 
     *
     * @param string $error_message The summary error message to be displayed to the user in place of the successful form response. Configure the default using VALIDATION_ERROR_MESSAGE
     * @return void
     */
    public function validateFormAfterSubmit( $clear = false ) {


        /*
         * Wait until Form is Validated Client Side
         * Client side validation is handled by 
         */

        if ( !$this->isValid(
                        $this->id(), //form identifier
                        $clear //whether to clear values on submission (regardless if valid)
                ) ) {
            return;


        }

        $this->_processValidationRules(); //this actually calls the methods for each rule and executes them
        return; //not sure fi we need the rest




    }

    /**
     * Process Validation Rules
     *
     * Executes the validation methods that have been mapped to the fields using setValidationRules()
     * @param none
     * @return void
     */
    private function _processValidationRules() {

        $all_rules = $this->getValidationRules();


        foreach ( $all_rules as $field_name => $rule_set ) {


            foreach ( $rule_set as $rule ) {

                $params = array();
                $method = null;
                $success_message = null;
                $error_message = null;
                $not = null;






                $field_value = $_POST[ $field_name ];



                $method = $rule[ 'callback' ];
                $success_message = $rule[ 'success_message' ];
                $error_message = $rule[ 'error_message' ];
                $not = $rule[ 'not' ];
                /*
                 * if no paramaters were passed,
                 * than create a paramaters array with the field value.
                 * if paramaters were passed, then add the field value to the 
                 * paramaters array so the original paramaters value becomes the second value.
                 */

                if ( empty( $rule[ 'params' ] ) ) {
                    $params = array( $field_value );
} else{
                    $params = array( $field_value, $rule[ 'params' ] );
}




                if ( is_callable( $method ) ) {

                    $validationResult = call_user_func_array( $method, $params );

} else{
                    /*
                     * if not callable, it means that we are going to assume that the 
                     * method resides in this class and will attempt to call it
                     */
                    $method = 'validate' . $method;
                    if ( method_exists( $this, $method ) ) {


                        $validationResult = call_user_func_array( array( $this, $method ), $params );



}


}

                /*
                 * Applies the $not operator
                 */
                $validationResult = ($not === true) ? !$validationResult : $validationResult;


                /*
                 * set validation error message and set invalid rule
                 */
                if ( $validationResult === false ){





                    if ( is_null( $error_message ) ) {
                        $error_message = $this->VALIDATION_FIELD_ERROR_MESSAGE;
}
                    /*
                     * Use the PHP Form Builder Class Error method
                     */

                    $this->setError( $this->id(), "$field_name: $error_message" );
                    $this->_is_valid_after_submit = false;

}

                /*
                 * Caution: Do not set $this->_is_valid_after_submit to true even if one rule 
                 * passes successfully. This is because with multiple rules, one may pass and 
                 * another may fail. Since default is pass, any single failure (without using an 'else' statement) will
                 * cause the entire thing to fail ( which is what we want)
                 */

} //end foreach loop to search for rule sets

}//end foreach to search for fields

    }

    /**
     * id
     *
     * Returns Form's Id
     *
     * @param none
     * @return string The id of the current form.
     */
    public function id() {
        return $this->getAttribute( 'id' );

    }

    private $_is_valid_after_submit = null;

    /**
     * Is Valid After Submit
     *
     * Returns whether the form is valid after processing submission rules
     *
     * @param none
     * @return void
     */
    public function isValidAfterSubmit() {

        /*
         * set default to true, and only set to false if 
         * a validation rule fails
         */
        if ( is_null( $this->_is_valid_after_submit ) ) {
            $this->_is_valid_after_submit = !empty( $_POST ); //we want it true if something was submitted. 

}


        return $this->_is_valid_after_submit;

    }

    /**
     * Validate Domain Name
     *
     * Validate Domain Name
     * ref: http://stackoverflow.com/a/10306731/3306354
     * no subdomains
     * no http
     * no slashes
     * 
     * @param string $string The domain name string to validate
     * @return void
     */
    public function validateDomainName( $domain_name ) {

        /*
         * Return True If Optional and Trim
         * 
         * This allows you to make this field optional
         * but still able to apply this rule when something is 
         * submitted.

         */
        if ( is_null( $domain_name ) ) {
            return true;
}


        preg_match(
                '/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/' //$pattern
                , $domain_name  //$sourcestring
                , $matches //$matches to hold matching substrings
        );



        /*
         * False if doesnt match the basic domain pattern
         */
        if ( empty( $matches ) === true ) {
            return false;
        } else{
            return true;
        }





    }

    /**
     * Validate Regex
     *
     * Use a regex expression to validate a form field
     *
     * Usage:
     * 
     * $form->setValidationRule(
      'domain', //field name to be validated
      array( $form, 'validateRegex' ), //callback
      '/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/', //paramaters
      'Bad Domain as validated by regex' //error message
      );

     * ref:http://www.myregextester.com/
     * test:regex_pattern (domain):'/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/'
     * 
     * @param none
     * @return void
     */
    public function validateRegex( $field_value, $regex_pattern ){

        preg_match(
                $regex_pattern //$pattern
                , $field_value  //$sourcestring
                , $matches //$matches to hold matching substrings
        );



        if ( empty( $matches ) === true ) {
            return false;
} else{
            return true;
}

    }

    /**
     * Validate Min Length
     *
     * Verifies that string is at least the minimum length
     *
     * 
     * Usage
     * $form->setValidationRule(
      'Password', //field name to be validated
      array( $form, 'validateMinLength' ), //callback
      2, //paramaters
      'Must be at least 2 characters long' //error message
      );
     * 
     * 
     * @param string $string The string to validate
     * @param string The minimum length of the string
     * 
     * @return void
     */
    public function validateMinLength( $string, $min_length ) {


        if ( strlen( trim( $string ) ) < $min_length ){

            return false;
    } else {
            return true;
    }
}

    /**
     * Validate Max Length
     *
     * Verifies that string is no longer than a max length.
     *
     * Usage:
     * $form->setValidationRule(
      'Password', //field name to be validated
      array( $form, 'validateMaxLength' ), //callback
      5, //paramaters
      'Too long! Cant be more than 5 characters long' //error message
      );
     * 
     * @param string $string The string to validate
     * @param string $max_length The maximum length of the string
     * 
     * @return void
     */
    public function validateMaxLength( $string, $max_length ) {

        if ( strlen( trim( $string ) ) > $max_length ){
            return false;
    } else {
            return true;
    }
 }

    /**
     * Validate List
     *
     * Verifies that the value is within an allowed list.
     *
     * 
     * Usage:
     * 
     * Example 1
     * $form->setValidationRule(
      'Password', //field name to be validated
      array( $form, 'validateList' ), //callback
      array('blue','red','yellow'), //paramaters
      "Must choose either 'blue','yellow',or 'red' " //error message

      );
     * Example 2
      $form->setValidationRule(
      'Password', //field name to be validated
      array( $form, 'validateList' ), //callback
      array('blue','red','yellow'), //paramaters
      'Must not be blue,red, or yellow', //error message
      true //applies the not operator. in this case, rule will pass if the submitted item is NOT on the list
      );

     * 
     * 
     * @param string $value The string to validate
     * @param array $params including 'list' The list of values that are allowed
     * 
     * @return void
     */
    public function validateList( $string, $list ) {


        if ( !is_array( $list ) || !in_array( $string, $list ) ) {
            return false;
} else {
            return true;
}

     }

    /**
     * Validate Email
     *
     * Validate Email
     * ref: http://stackoverflow.com/q/3722831/3306354
     * @param $email
     * @return void
     */
    public function validateEmail( $email ) {

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


        $validation_result = filter_var( $email, FILTER_VALIDATE_EMAIL );


        return $validation_result;

     }

    /**
     * Set Ajax Callback
     *
     * This fixes a bug with PHPBC version 3.1 which fails to set this property with configure() due to this method apparently missing from PHBC.
     *  Sets the PHBC's  Form Class's ajaxCallback property. It shouldn't be needed to be called directly but is called via PHBC's Base class's configure() method. 
     *
     * @param string $callback The javascript function that processes the ajax response 
     * @return void
     */
    public function setAjaxCallback( $callback ) {

        $this->ajaxCallback = $callback;
}

    /**
     * Set Ajax Callback
     *
     * This fixes a bug with PHPBC version 3.1 which fails to set this property with configure() due to this method apparently missing from PHBC.
     *  Sets the PHBC's  Form Class's ajax property. It shouldn't be needed to be called directly but is called via PHBC's Base class's configure() method. 
     *
     * @param string $callback The javascript function that processes the ajax response 
     * @return void
     */
    public function setAjax( $ajax ) {

        $this->ajax = $ajax;
}

    /**
     * Set Prevent
     *
     * This fixes a bug with PHPBC version 3.1 which fails to set this property with configure() due to this method apparently missing from PHBC.
     *  Sets the PHBC's  Form Class's  'prevent' property. It shouldn't be needed to be called directly but is called via PHBC's Base class's configure() method. 
     *
     * @param string $callback The javascript function that processes the ajax response 
     * @return void
     */
    public function setPrevent( $parms ) {

        $this->prevent = $parms;
}

    /**
     * Set Ajax Options
     *
     * Sets options that become available in javascript using the setPluginScriptVar()  from downcast()
     *
     * @param none
     * @return void
     */
    public function setAjaxOptions( $_options ) {

        $all_script_vars=$this->downcast()->getScriptVars();
        $form_options=$all_script_vars['plugins']['Forms'][$this->id()];
        $form_options=(!is_array($form_options))?array($form_options):$form_options;

        /*
         * merge existing with new 
         */
        $options=array_merge($form_options,$_options);

        $defaults = array(
         'action' => 'action_warning', 
            'response_target' => 'downcast_response_target', //the id of the DOM element that should display any messages       
            'hide_on_success' => true, //hides the form on success
            'collapse_on_hide' => false, //completely removes all form html from page when form is hidden
            'reset_on_success' => true, //reset form after successful submission
            'success_message' => '<div class="alert alert-success"><a class="close" href="#" data-dismiss="alert">×</a>Thank you for your submission!</div>', //default success message  //the default success message if the form handler does not supply one.
            'error_message' => '<div class="alert alert-danger"><a class="close" href="#" data-dismiss="alert">×</a>Your submission was unable to be processed. Please try again later.</div>' //the default error message if the form handler does not supply one.
        );
      //  $this->downcast()->debugLog( '$defaults = ', $defaults, true, false );
     //   $this->downcast()->debugLog( '$options = ', $options, true, false );

      //  $this->downcast()->debugLog( '$options = ', array_filter($options,array($this,'isNotNull')), true, false );

        $options = $this->downcast()->screenDefaults(
                $defaults, //only items in defaults will make it through
                array_filter($options,array($this,'isNotNull')) //options will overwrite defaults unless null, then defaults will be used.array_filter unsets values that are null, allowing defaults to take their place
        );
   //     $this->downcast()->debugLog( '$options = ', $options, true, false );


        /*
         * Downcast.Forms.MyFormId.options
         */
       $this->downcast()->setPluginScriptVar( 'Forms', $this->id(), $options );

     


    }

    /**
     * Handles Ajax Form
     *
     * Validates the form and calls $callback if it validates
     *
     * @param none
     * @return void
     */
    public function handleAjaxForm( $callback ) {

        /*
         * Cannot process unless this is a post
         * and the post contains 'form' which is the form's dom id
         * Cannot use isAjax() since that will not catch validation before submitting.
         */
        if ( !isset( $_POST[ 'form' ] ) ) {
            return;

        }
        /*
         * Apply Server Side Validation
         * 
         */

        $this->validateFormAfterSubmit(
                true  // Whether to clear values after successful client side validation. Note that form is not affected by this setting since we are operating as an ajax form (elements are not visible from handleAjaxForm method). instead, control it via setAjaxOptions option reset_on_success
        );

        /*
         * 
         */
        if ( $this->isValidAfterSubmit() ){
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
            call_user_func( $callback );


    } else
            self::renderAjaxErrorResponse( $this->id() );
        exit();



    }

    /**
     * Add Element
     *
     * Wrapper around Form::addElement so we can check if its ajax first
     * We'd have to wrap everything in a if $_POST check anyway without it
     *
     * @param none
     * @return void
     */
    public function addElement( Element $element ) {
        if ( !$this->downcast()->isAjax() ) {


            parent::addElement( $element );
    }

    }

    /**
     * Render
     *
     * Wrapper around Form::render so we can check if its ajax first
     * We'd have to wrap everything in a if $_POST check anyway without it
     *
     * @param none
     * @return void
     */
    public function render() {
        if ( !$this->downcast()->isAjax() ) {


            parent::render( $returnHTML = false );
    }

}

    /**
     * Is Not Null
     *
     * Used as Array Filtering Callback
     *
     * @param mixed $val
     * @return bool True if not null, false otherwise
     */
    public function isNotNull( $val ) {
       return !is_null($val);         

    }
}

?>
