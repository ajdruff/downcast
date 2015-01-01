##Forms Plugin


<!-- image references -->
[my-first-form]: /plugins/Forms/content/img/my-first-form.png  "My First Form after submission"

###Description

 The DownCast Forms plugin provides form support required by other plugins, including Plugin Manager.   
 
 It also allows you to easily create Ajax and non-Ajax forms that can be 
 configured to use both server side and client side validation.
 
###About
The DownCast Forms Plugin is an extension of the popular [PHP Form Builder Class](http://www.imavex.com/pfbc3.x-php5/index.php)

The PHP Form Builder Class:  

* allows you to quickly create forms using simple PHP method calls without writing HTML
* adds simple client and server side validation

Downcast adds the following features:  

* easy ajax form creation without writing any javascript
* extra server side validation rules
 
### Activating the Plugin

Activate the Forms plugin by setting 'active' to true in the site's config.json file:

```  
    "PLUGINS":
            {
                "Forms":
                        {"active": true}
            }

```  

>If all you need to do is to install the plugin so you can use dependent plugins, then you're done. If you want to create your own forms though,read on.
{: .alert .alert-info}

##Example Forms

There are several example forms located in `/plugins/Forms/content/phbc-form-examples/`.

To view these forms, the easiest way is to activate the DemoForms plugin.

*Installing Example Forms by activating the DemoForms plugin*

* Edit your site's configuration file, `config.json`, and add the following under the `PLUGINS` section: 
```
    "DemoForms":
            {"active": true}
```                                                


Once activated, you'll see a dropdown appear in your sidebar that launches 
each example form. If you don't see the dropdown, see the DemoForms readme for
information on how to add it or how to relocate it if your template doesnt have a sidebar.

If you have the `ViewSource` plugin installed, you will also be 
able to view the source of each form without opening a new window.

*To install the example forms without using the DemoForms plugin*

1. Edit `/plugins/Forms/Forms.php`
2. add a line similar to the following for each of the example forms contained 
in `/plugins/Forms/content/phbc-form-examples`
```
    $this->downcast()->addPage( '/any/url/you/want/', dirname( __FILE__ ) . '/path/to/form/file.php' );
```

So to add DownCast's ajax login form, we'd add: :
```
$this->downcast()->addPage( '/form/examples/ajax/login/downcast/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login-downcast.php' );
```

##Getting Started

###To create your first form: 

 1. Edit `/plugins/Forms/Forms.php` and add the following line to the `config()` method: 
    ```
    $this->downcast()->addPage( '/my/first/form/', dirname( __FILE__ ) . '/content/my-first-form.php' );
    ```

 2. Create a new php file, `my-first-form.php` in the `/plugin/Forms/content` directory.

 3. Edit `/plugin/Forms/content/my-first-form.php` and add the following code: 
    ```
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
    ```
 4. Now visit /my/first/form/ , fill out the form and submit.
 5. Once the form is submitted, you should see:


![][my-first-form]
{: .thumbnail}
 
>This is a very basic example and does not provide any server side validation. For more examples that include additional validation rules, check out the [example forms](#Example%20Forms) 'Getting Started' and see the [PHP Form Builder class documentation](http://www.imavex.com/pfbc3.x-php5/index.php#getting-started)
{: .alert .alert-info}









###Ajax
Ajax allows you to submit a form without having to refresh the entire page since it only updates the parts of the page that changes after a user clicks the form's submit button.


The DownCast Forms plugin makes creating Ajax forms as easy as creating a non-ajax form. 

####Walkthrough - Create Your First Ajax form

 1. Edit `/plugins/Forms/Forms.php` and add the following line to the `config()` method: 
    ```
    $this->downcast()->addPage( '/my/first/ajax/form/', dirname( __FILE__ ) . '/content/my-first-ajax-form.php' );
    ```

 2. Create a new php file, `my-first-ajax-form.php` in the `/plugin/Forms/content` directory.

 3. Edit `/plugin/Forms/content/my-first-ajax-form.php` and add the following code: 
 ```
    <?php


    /*
    * Declare the form object with our Form's ID
    */

    $form = new DowncastForm(
           "myform", // form id , arbitrary string that is unique for the form
           true //$ajax whether we want the form to use ajax. 
    );



    /*
    * Add Server Side Validation Rules
    * 
    */
    $form->setValidationRule(
           'Password', //field name to be validated
           array( $form, 'validateMaxLength' ), //callback
           5, //paramaters
           'Too long! Password can\'t be more than 5 characters long' //error message
    );




    /*
    * Add Form Elements
    */
    $form->addElement( new Element_HTML( '<legend>Login</legend>' ) );
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



    include(dirname(__FILE__) . '/ajaxFormHandler.php');

    /*
    * Add Form Handler
    * Must be called after class with method is defined
    */

    $form->handleAjaxForm(
          'ajaxFormHandler'//callback
    );
    ?>
 ```  
 4. Create the file 'ajaxFormHandler.php' and add it to the `/plugins/Forms/content' directory.
 5. Add the following code to 'ajaxFormHandler.php':  
```
if ( !function_exists( 'MyLoginForm' ) ) {
    function ajaxFormHandler()
    {


        /*
         * your code to validate user
         * 
         */


        $response[ 'success' ] = true;

        $response[ 'form' ] = $_POST[ 'form' ];
        $response_json = json_encode( $response );
        echo $response_json;
    }


    }
```

###Validation
Validation allows you to add all the input checking you need to make sure that the data that the user is submitting is valid, before further processing.

The DownCast Forms plugin allows you to use any validation rule that is provided by the PHP Form Builder Class as described in the [PFBC Validation documentation(http://www.imavex.com/pfbc3.x-php5/examples/validation.php) documentation], but also provides a number of useful server-side based rules that can further validate your form. See [DownCast Validation Rules](#DownCast%20Validation%20Rules) for more. You can even create your own custom rule ( see the example forms for more information). 


####Walkthrough - Add Validation Rules to Your Form