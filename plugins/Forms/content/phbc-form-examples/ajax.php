<?php
/**
 * Form Example - Ajax
 * 

 * 
 * 
 * 

 * 

 * These examples are based on the examples provided by the PHP Form Builder Class project
 * They are modified slightly to take advantage of the DowncastForm class which extends the PFBC's functionality
 * to provide server side validation using the setValidationRule method
 * @link:http://www.imavex.com/pfbc3.x-php5/examples/ajax.php
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @filesource
 */

if ( !( $_POST[ "form" ] ) ) {
    

/*
 * Add a 'src' tag via the ViewSource plugin
 * see / for more info {VIEW_SOURCE}
 */
?>
{VIEW_SOURCE}
###Demo Form - Ajax




<button type="button" class="btn btn-warning collapsed" data-toggle="collapse" data-target="#about">
About this Demo
</button>



* **Basic Ajax Form**
* Requires the DownCast Forms Plugin which uses the [PHP Form Builder Class](http://www.imavex.com/pfbc3.x-php5/index.php), a popular PHP forms framework
* This demo is based on th [PHP Form Builder Class 'Ajax' Example](http://www.imavex.com/pfbc3.x-php5/examples/ajax.php) 

* To use:
    1. Install the Forms Plugin
    2.  place the following in the Forms plugin config() method : 
    ```

    $this->downcast()->addPage( '/form/examples/ajax/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax.php' );

    ```
* Source located at `<?php echo $this->file_getRelativePath( __FILE__ ); ?>`
* The expected behavior of this form is to return a latitude/longitude of a location:  
![{: .img-rounded}](/plugins/Forms/content/img/ajax.png){: .alert .alert-info}
* If you are not seeing the Latitude/Longitude returning, verify that url_fopen option in php.ini is set to On.  
    ```

    allow_url_fopen = On

    ```
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
        "ajaxCallback" => "parseJSONResponse"
    ) );
    $form->addElement( new Element_Hidden( "form", "ajax" ) );
    $form->addElement( new Element_HTML( '<legend>Using the Google Geocoding API</legend>' ) );
    $form->addElement( new Element_HTML( '<div>Enter a location, e.g.: Seattle,WA to see the latitude and longitude</div>' ) );
    $form->addElement( new Element_Textbox( "Address:", "Address", array(
        "required" => 1
    ) ) );

    /*
     * set action to this form
     */
    $form->setAttribute( 'action', '/form/examples/ajax/' );

    $form->addElement( new Element_HTML( '<div id="GoogleGeocodeAPIReaponse" style="display: none;">' ) );
    $form->addElement( new Element_Textbox( "Latitude/Longitude:", "LatitudeLongitude", array(
        "readonly" => ""
    ) ) );
    $form->addElement( new Element_HTML( '</div>' ) );
    $form->addElement( new Element_Button( "Geocode", "submit", array(
        "icon" => "search"
    ) ) );
    $form->render();
    ?><script type="text/javascript">
        function parseJSONResponse(latlng) {
            var form = document.getElementById("ajax");
            if (latlng.status == "OK") {
                var result = latlng.results[0];
                form.LatitudeLongitude.value = result.geometry.location.lat + ', ' + result.geometry.location.lng;
            }
            else
                form.LatitudeLongitude.value = "N/A";

            document.getElementById("GoogleGeocodeAPIReaponse").style.display = "block";
        }
    </script><?php
}

//----------AFTER THE FORM HAS BEEN SUBMITTED----------
if ( isset( $_POST[ "form" ] ) ) {
    if ( DowncastForm::isValid( $_POST[ "form" ] ) ) {
        header( "Content-type: application/json" );
        echo file_get_contents( "http://maps.google.com/maps/api/geocode/json?address=" . urlencode( $_POST[ "Address" ] ) . "&sensor=false" );
    } else
        Form::renderAjaxErrorResponse( $_POST[ "form" ] );
    exit();
    }

