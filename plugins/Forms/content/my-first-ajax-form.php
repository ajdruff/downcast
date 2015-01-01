<?php


/*
 * Declare the form object with our Form's ID
 */

$form = new DowncastForm(
        "myform", // form id , arbitrary string that is unique for the form
        true, //$ajax whether we want the form to use ajax. 
        'MyAction'
);


if (true){
$options = array(
//'action'=>'MyAction',//this must be 
    'hide_on_success' => true, //hides the form on success
    'collapse_on_hide' => true, //completely removes all form html from page when form is hidden);
    'reset_on_success' => false
);
$form->setAjaxOptions( $options );
}

/*
 * Add Form Elements
 */
$form->addElement( new Element_HTML( '<legend>Login</legend>' ) );
$form->addElement( new Element_Email( "Email Address:", "Email", array( 'value'=>'adruff@msn.com',"required" => 1 ) ) );
$form->addElement( new Element_Textbox( "Password:", "Password", array('value'=>'12345',
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

