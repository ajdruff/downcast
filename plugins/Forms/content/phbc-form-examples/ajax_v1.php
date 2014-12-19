
<script type="text/javascript">
        function parseJSONResponse(response) {
               console.log(response);
               alert('you got me');
        }
    window.onload = function(){
        function parseJSONResponse(response) {
               console.log(response);
               alert('you got me');
        }
}

</script>

<?php
/**
 * Form Example - Ajax
 * 
 * 
 * Usage:
 * 
 * Enable the 'Forms' plugin
 * Map a url to this form by adding the following to the config() method of the Forms Plugin: 
 *  $this->downcast()->addPage( '/form/examples/ajax/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax.php' );
 * 
 * @link:http://www.imavex.com/pfbc3.x-php5/examples/ajax.php
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

if ( $form->isValidAfterSubmit() ){
    /*
      Form::clearValues( $form->id() ); //clear values if submission is successful
      echo '<br> Thank you for your submission.';
     */

    //----------AFTER THE FORM HAS BEEN SUBMITTED----------
    //  echo '<pre>', print_r( $_POST, true ), '</pre>';
    if ( isset( $_POST[ "form" ] ) ) {
        if ( Form::isValid( $_POST[ "form" ] ) ) {
//header("Content-type: application/json");
//echo file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . urlencode($_POST["Address"]) . "&sensor=false");
            echo '<br>this is the ajax response';


} else
            Form::renderAjaxErrorResponse( $_POST[ "form" ] );
        exit();
}






}



$form->configure( array(
    "ajax" => 1,
    "ajaxCallback" => "parseJSONResponse"
) );

/*
 * Form Handler
 * Optionally, provide a different form handler
 * Otherwise, it will use this same script when submitted
 * $form->setAttribute( 'action', '/your/form-handler/url/here/' );
 */
$form->setAttribute( 'action', '/form/examples/ajax/' );

$form->addElement( new Element_Hidden( "form", "ajax" ) );
$form->addElement( new Element_HTML( '<legend>Using the Google Geocoding API</legend>' ) );
$form->addElement( new Element_Textbox( "Address:", "Address", array(
    "required" => 1
) ) );
$form->addElement( new Element_HTML( '<div id="GoogleGeocodeAPIReaponse" style="display: none;">' ) );
$form->addElement( new Element_Textbox( "Latitude/Longitude:", "LatitudeLongitude", array(
    "readonly" => ""
) ) );
$form->addElement( new Element_HTML( '</div>' ) );
$form->addElement( new Element_Button( "Geocode", "submit", array(
    "icon" => "search"
) ) );
$form->render();
?>

