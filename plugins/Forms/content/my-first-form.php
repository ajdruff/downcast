<?php
$form = new DowncastForm(
        'login'  // form id , arbitrary string that is unique for the form
);


if ( $form->isValidAfterSubmit() ){
    
    /*
     * Add any code here that needs 
     * to be executed when the form is submitted
     */
    
    Form::clearValues( $form->id() ); //clear values if submission is successful

    /*
     * Add a message back to the user that their submission was 
     * successful
     */
    echo '<div class="alert alert-success">Thank you for your submission.</div>';

}

$form->addElement( new Element_HTML( '<legend>Login</legend>' ) );
$form->addElement( new Element_Hidden( "form", $form_id ) );

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
?>