##Forms Plugin


<!-- image references -->
[my-first-form]: /plugins/Forms/content/img/my-first-form.png  "My First Form after submission"
[ajax-no-action]: /plugins/Forms/content/img/ajax-no-action.png  "Ajax Form - No action configured"
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

####Walkthrough - Create A Basic Form

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

####Walkthrough - Create An Ajax form (Example 1)

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
                tru, //$ajax whether we want the form to use ajax. 

        );



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
    ```
 

 
 4. Now fill the form out and submit it. You'll see something like this as a result:
 
 ![][ajax-no-action]
{: .thumbnail}

 5. The message `No action has been configured for this form. Please see the Help documentation for the DownCast Forms plugin to fix this.` means that you still need to tell the form what to do with the submitted input. You do this by adding an 'action' and then coding the action to handle the form's submission. To add an action, see the next section 'Ajax - Adding Ajax Actions'.
 
 ####Ajax - Adding Ajax Actions
 
 Ajax forms require adding an action to handle the form's input. An action is a method that you create to further validate the form's input and then do something with the data in the form field. 
 
 The Downcast Forms plugin gives you 2  ways of adding an action. The first requires calling `addAjaxAction` and therefore requires more code, but gives you greater flexibility in the naming and location of the method you use to create the action. The second method is provided for conveniance and allows you to skip the `addAjaxAction` method.
 
 ####Creating an Ajax Action: Method 1 
 
 Method 1 involves calling the `addAjaxAction` method
 
 
1. Add the following code to the `config()` method of the Forms class in `/plugins/Forms/Forms.php`


        ```  
            $this->addAjaxAction(
                'MyAction', //action name submitted by form
                array( $this, 'MyAction' ) //callback method to call when action is triggered
            );
        ```  

2. Edit your form to add the action name as the 3rd argument to your form's creation code:

        ```  
            $form = new DowncastForm(
                    "myform", // form id , arbitrary string that is unique for the form
                    true, //$ajax whether we want the form to use ajax. 
                    'MyAction'

            );
        ```  

3. Code your form's action.
Add the method for your form's action by adding the following code to the Forms class in `/plugins/Forms/Forms.php`
        ```  
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
        ```   
        
        
>This is a very basic action method. For a more complete discussion of how to code a form's action, please see the section [Coding a Form's action](#Coding%20a%20Form's%20action)        
{: .alert .alert-info}        

       
 
####Creating an Ajax Action: Method 2

 
 Method 2 is simpler than Method 1, but requires adhering to a naming convention for your method, and locating the method within the Forms class.
 
 
1.  Edit the form creation code by adding a 3rd argument to your form's creation code: 


    ```
        $form = new DowncastForm(
              "myform", // form id , arbitrary string that is unique for the form
              true, //$ajax whether we want the form to use ajax. 
              'MyAction' // The Ajax action. Triggers Forms->
        );
    ```
2. Add the following code to /plugins/Forms/Forms.php:

    ```

    /**
         * An Example Ajax Action
         *
         *
         * @param none
         * @return void
         */
        public function formAjaxActionMyAction() {
            $result['success']=true;
            $result['success_message']='you triggered '.__FUNCTION__;
            $result['form']=$_POST['form'];
            echo json_encode($result);
        }
    ```


>**Why this works** 
>This works because if you don't specifically add an action using the `addAjaxAction` method, Downcast looks for a method within the `Forms` class that starts with `formAjaxAction` and ends with the name of your action. This means that if you choose to use method 2,  you must always locate your action's method within the Forms class and always adhere to the naming convention `formAjaxAction<name-of-your-action>`. It also means that any action name must adhere to the same naming rules as any php function, using only letters, numbers, or underscores. No spaces or other special characters are allowed.
    


This is a very basic action method. For a more complete discussion of how to code a form's action, please see the section [Coding a Form's action](#Coding%20a%20Form's%20action)
{: .alert .alert-info}        


####Coding a Form's action

When you code a form's action, you'll want to do the following :  

1. Validate the input ( optional but strongly recommended ) 
2. Handle the form's data
3. Return a result to the user.
#####Validate the input 


Validating the form's input in this case means validating the form fields' data after the form has been submittted. This validation is in addition to the validation that you may already have done when you added your elements to the form, which were handled client side. Here, the validation occurs on the server and you can use the results to decide whether to return a success or error message back to the user.

```
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
```


#####Handle the form's data

Coding the form's action method to handle the input data is the functionality that gives value back to the user who is submitting the form. You will take some action, like validate the user against your database, lookup a search result, or email someone. In the simplest case, you'll provide a simple message back to the user. 


In all cases, you will be pulling the form's data from php's [$_POST array](http://php.net/manual/en/reserved.variables.post.php).

In the example below, the `sendEmail` method is called using the submitted `email` and `message` form fields. This assumes that the form submitted includes these fields and validation has already occurred, so we don't need to worry whether these fields are empty or otherwise invalid. The `sendEmail` method in this example will return a true or false result depending on the success of sending the email. Note that the `sendEmail` method isn't provided by the plugin - it is used here only as an example.

```

    $result['success']=$this->sendEmail($_POST['email'],$_POST['message']);


```



#####Return a result to the user

You should never echo any message back to the user since any direct output from your code will break the ajax handler. Instead, return your results as a json encoded response.


The Forms plugin will display a message back to the user that depends on the results of your form's action. 

**Within the form's action code, you must return the following as json:**

* $response[ 'success' ] 
    * boolean, true for success, false for failure
* $response[ 'form' ]
    * Should always be set to equal $_POST['form'], which is the form's id. By returning this parameter back to the ajax javascript handler, the response can be targeted to the correct form in the event that multiple form's are on the same page.
         
**The following elements are optional:**  

* $response['success_message']
    * if not supplied, the form's default success message will be used, configurable using $form->setAjaxOptions
* $response['error_message']
    * if not supplied, the form's default error message will be used, configurable using $form->setAjaxOptions
         

Once you have set the $result array's elements to the values you want, you must encode the entire array as json.
        
Example code to be added to your action's method is provided below:


```
    $result['success']=true;
    $result['success_message']='<p>Your message has been sent!</p>';
    $result['form']=$_POST['form'];
    echo json_encode($result);
```
    
 
alternately: 
```
    $result['success']=false;
    $result['error_message']='<p>Your message could not be sent due to an unknown error. Please try again later.</p>';
    $result['form']=$_POST['form'];
    echo json_encode($result);
```
 
####Walkthrough - Create An Ajax form (Example 2)

In this example, we'll create the same form as in Example 1, but we'll add an action that will validate and return a success or failure result back to the user.

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
                true, //$ajax whether we want the form to use ajax. 
                'MyAction'
        );



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
    ```  
    
4. Add the form's action by using method 2. See []() for a more complete discussion on how to add actions.

   Add the following code to /plugins/Forms/Forms.php:

    ```
    
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
    ```

5. Now browse to /my-first-ajax-form/ and submit the form. You should get back a response that      

###Validation
Validation allows you to add all the input checking you need to make sure that the data that the user is submitting is valid, before further processing.

The DownCast Forms plugin allows you to use any validation rule that is provided by the PHP Form Builder Class as described in the [PFBC Validation documentation(http://www.imavex.com/pfbc3.x-php5/examples/validation.php) documentation], but also provides a number of useful server-side based rules that can further validate your form. See [DownCast Validation Rules](#DownCast%20Validation%20Rules) for more. You can even create your own custom rule ( see the example forms for more information). 


####Walkthrough - Add Validation Rules to Your Form