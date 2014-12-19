<?php
if ( empty( $_POST ) ) {
        $form = new Form( "ajax" );
    $form->configure( array(
        "ajax" => 1,
        "ajaxCallback" => "parseJSONResponse"
    ) );
    
    
    $form->addElement( new Element_Hidden( "form", "ajax" ) );
    $form->setAttribute( 'action', '/form/examples/ajax/' );
  //  $form->setAttribute( 'action', '/plugins/Forms/content/phbc-form-examples/ajax-form-handler.php' );
    
    $form->configure(array("action"=>"post",
 "ajax"=>"1",
"ajaxCallback" => "parseJSONResponse")); 
   
        
    $form->addElement( new Element_HTML( '<legend>Using the Google Geocoding API</legend>' ) );
    $form->addElement( new Element_Textbox( "Address:", "Address", array(
        "required" => 1
    ) ) );
//$form->addElement( new Element_HTML( '<div id="GoogleGeocodeAPIReaponse" style="display: none;">' ) );
    $form->addElement( new Element_Textbox( "Latitude/Longitude:", "LatitudeLongitude", array(
        "readonly" => ""
    ) ) );
//$form->addElement( new Element_HTML( '</div>' ) );
    $form->addElement( new Element_Button( "Geocode", "submit", array(
        "icon" => "search"
    ) ) );
    $form->render();
}
//----------AFTER THE FORM HAS BEEN SUBMITTED----------

if ( isset($_POST[ "form" ] )) {
    if ( Form::isValid( $_POST[ "form" ] ) ) {


        //echo file_get_contents( "http://maps.google.com/maps/api/geocode/json?address=" . urlencode( $_POST[ "Address" ] ) . "&sensor=false" );
        $result_array[ 'status' ] = 'OK';

        $result_json = json_encode( $result_array );
        header( "Content-type: application/json" );
        echo $result_json;
        exit();
    } else
        Form::renderAjaxErrorResponse( $_POST[ "form" ] );
    exit();
    }
?><script type="text/javascript">
    window.onload = function(){
    function parseJSONResponse(latlng) {
        alert(latlng.status);
    }
    }
        function parseJSONResponse(latlng) {
        alert(latlng.status);
    }
</script>