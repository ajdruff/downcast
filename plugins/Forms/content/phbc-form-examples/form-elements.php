<?php

/**
 * Form Example - Form Elements
 * 
 * 
 * Usage:
 * 
 * Enable the 'Forms' plugin
 * Map a url to this form by adding the following to the config() method of the Forms Plugin: 
 *  $this->downcast()->addPage( '/form/examples/form-elements/', dirname( __FILE__ ) . '/content/phbc-form-examples/form-elements.php' );
 * 
 * @link:http://www.imavex.com/pfbc3.x-php5/examples/form-elements.php
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
 * Add a 'src' tag via the ViewSource plugin
 * see / for more info {VIEW_SOURCE}
 */

?>{VIEW_SOURCE}<?php
 


/*
 * Declare the form object with our Form's ID
 */
$form = new DowncastForm(
        "form-elements"  // form id , arbitrary string that is unique for the form
);

 ?>

###Demo Form - Form Elements


<button type="button" class="btn btn-warning collapsed" data-toggle="collapse" data-target="#about">
About this Demo
</button>


* Form Demo shows all available PHP Form Builder Class Elements
* Requires the DownCast Forms Plugin which uses the [PHP Form Builder Class](http://www.imavex.com/pfbc3.x-php5/index.php), a popular PHP forms framework
* This demo is based on the [PHP Form Builder Class 'Form Elements' Example](http://www.imavex.com/pfbc3.x-php5/examples/form-elements.php) but uses validation extensions added by the DowncastForm class.
* To use:
    1. Install the Forms Plugin
    2.  place the following in the Forms plugin config() method : 
    ```

            $this->downcast()->addPage( '/form/examples/form-elements/', dirname( __FILE__ ) . '/content/phbc-form-examples/form-elements.php' );

    ```
    3\. browse to the [form](/form/examples/form-elements/)

* Source located at `<?php echo $this->file_getRelativePath(__FILE__); ?>`   
{: id="about" class="collapse" }

<?php
 /*
  * Add Server Side Validation Rules
  * 
  * For this example, no Server Side Validation Rules will be added
  * As long as it passes the client side validation, it will receive a 'thank you for your submission' message
  * 
  * For an example using validation rules see getting-started-ssvalidation.php
  */
 
if ( $form->isValidAfterSubmit()){
    
    Form::clearValues( $form->id() ); //clear values if submission is successful
    echo '<br> Thank you for your submission.';
    
}




/*
 * Form Handler
 * Optionally, provide a different form handler
 * Otherwise, it will use this same script when submitted
 * $form->setAttribute( 'action', '/your/form-handler/url/here/' );
 */

/*
 * Set Dropdown Options
 */
$options = array("Option #1", "Option #2", "Option #3");


$form->addElement(new Element_Hidden("form", "form-elements"));
$form->addElement(new Element_HTML('<legend>Standard</legend>'));
$form->addElement(new Element_Textbox("Textbox:", "Textbox"));
$form->addElement(new Element_Password("Password:", "Password"));
$form->addElement(new Element_File("File:", "File"));
$form->addElement(new Element_Textarea("Textarea:", "Textarea"));
$form->addElement(new Element_Select("Select:", "Select", $options));
$form->addElement(new Element_Radio("Radio Buttons:", "RadioButtons", $options));
$form->addElement(new Element_Checkbox("Checkboxes:", "Checkboxes", $options));
$form->addElement(new Element_HTML('<legend>HTML5</legend>'));
$form->addElement(new Element_Phone("Phone:", "Phone"));
$form->addElement(new Element_Search("Search:", "Search"));
$form->addElement(new Element_Url("Url:", "Url"));
$form->addElement(new Element_Email("Email:", "Email"));
$form->addElement(new Element_Date("Date:", "Date"));
$form->addElement(new Element_DateTime("DateTime:", "DateTime"));
$form->addElement(new Element_DateTimeLocal("DateTime-Local:", "DateTimeLocal"));
$form->addElement(new Element_Month("Month:", "Month"));
$form->addElement(new Element_Week("Week:", "Week"));
$form->addElement(new Element_Time("Time:", "Time"));
$form->addElement(new Element_Number("Number:", "Number"));
$form->addElement(new Element_Range("Range:", "Range"));
$form->addElement(new Element_Color("Color:", "Color"));
$form->addElement(new Element_HTML('<legend>jQuery UI</legend>'));
$form->addElement(new Element_jQueryUIDate("Date:", "jQueryUIDate"));
$form->addElement(new Element_Checksort("Checksort:", "Checksort", $options));
$form->addElement(new Element_Sort("Sort:", "Sort", $options));
$form->addElement(new Element_HTML('<legend>WYSIWYG Editor</legend>'));
$form->addElement(new Element_TinyMCE("TinyMCE:", "TinyMCE"));
$form->addElement(new Element_CKEditor("CKEditor:", "CKEditor"));
$form->addElement(new Element_HTML('<legend>Custom/Other</legend>'));
$form->addElement(new Element_State("State:", "State"));
$form->addElement(new Element_Country("Country:", "Country"));
$form->addElement(new Element_YesNo("Yes/No:", "YesNo"));
$form->addElement(new Element_Captcha("Captcha:"));
$form->addElement(new Element_Button);
$form->addElement(new Element_Button("Cancel", "button", array(
    "onclick" => "history.go(-1);"
)));
$form->render();


?>