
/**
 * forms.js
 * 
 * Provides a default Ajax form handler for a form
 * 
 * 
 * @author <andrew@nomstock.com>
 * @param none
 * @return none
 */


if (typeof DowncastPlugins === 'undefined') {
    var DowncastPlugins = {};
}

if (typeof DowncastPlugins.Forms === 'undefined') {
    DowncastPlugins.Forms = {};
}





DowncastPlugins.Forms.Forms = {
    /**
     * 
     * The response target.
     * We need it available so 
     * the submit event can clear it
     * 
     * @type {object}
     **/
    response_target: {},
    /**
     * todo: narrow this selector to only those forms created by forms
     * @type {Event}
     **/
    submitEvent: $("form").submit(function(e) {

/*
 * if Downcast.plugins is not available, return 
 * this is so that forms using    
 */
        if (typeof Downcast.plugins ==='undefined') {
          return true;  
        }
        var form_id = $(this).attr('id');
        var form=$(this);
        /*
         * Add the ajax action form field
         * This is consumed by ajax.php to figure out which method to call to process the form
         */
        form.append('<input name="dc_ajax_action" type="hidden" value="' + Downcast.plugins.Forms[form_id].action + '">');


        /*
         * Form Submit Event
         * 
         * On Form Submit, clear the most recent response 
         * if it exists
         */

        var response_target = DowncastPlugins.Forms.Forms.response_target;

        /* 
         * Clear the Response Target
         * 
         * If there is already a response, 
         * clear it in case an error occurs ( we dont want a success message AND an error displayed)
         */
        if (response_target.length > 0) {
            if (response_target.html() !== '') {
                response_target.css('visibility', 'hidden');
            }

        }




    }),
    // },
    /**
     * Ajax Response Handler
     *
     * Long
     * @param string response The json response from the ajax call
     * @return void
     */
    ajaxResponseHandler: function(response) {

        console.log(Downcast);

        console.log(response);


        var form_id = response.form;


        var form = $("#" + form_id);

        /*
         * look for the response_target 
         * if not found, create one that is unique to the form
         */


        var response_target_id = Downcast.plugins.Forms[form_id].response_target;

        console.log(response_target_id);
        var response_target = $("#" + response_target_id);
        if (response_target.length === 0) {
            response_target_id = response_target_id + '_' + form_id;
            form.before('<div id="' + response_target_id + '"><!--Ajax Response Here --></div>');
            response_target = $("#" + response_target_id);
        } else {



        }
        DowncastPlugins.Forms.Forms.response_target = response_target;
        //  var response_target = $("#" + Downcast.plugins.Forms[form_id].response_target);

        var hide_on_success = Downcast.plugins.Forms[response.form].hide_on_success;
        var collapse_on_hide = Downcast.plugins.Forms[response.form].collapse_on_hide;
        /*
         * Set messages equal to resonse messages 
         * If they aren't defined, use defaults.
         */



        var success_message =
                (typeof response.success_message === 'undefined')
                ? Downcast.plugins.Forms[response.form].success_message
                : response.success_message;
        var error_message =
                (typeof response.error_message === 'undefined')
                ? Downcast.plugins.Forms[response.form].error_message
                : response.error_message;


        /*
         * clear message
         */
        response_target.html();

        if (response.success) {
            console.log('success');
            response_target.html(success_message);
            if (Downcast.plugins.Forms[response.form].reset_on_success) {
                form[0].reset();//resets form after submission 
            }
            if (hide_on_success) {

                if (collapse_on_hide) {
                    form.hide();//this will keep the form in place, so the structure of the page won't shrink when it goes away. if you want it completely removed use '.css('display','none'); or .hide();
                }
                else {
                    form.css('visibility', 'hidden');

                }

            }


        }
        else {
            console.log('error');
            response_target.html(error_message);

        }

        /*
         * Make sure the response is visible
         * It will be hidden if the user submitted twice
         */
        if (response_target.length > 0) {

            response_target.css('visibility', 'visible');


        }


    }



}

jQuery(document).ready(function() {

});




