
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
     * Ajax Response Handler
     *
     * Long
     * @package MintForms
     * @since 0.1.1
     * @uses
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    ajaxResponseHandler: function(response) {

        console.log(Downcast);




        var form_id = response.form;


        var form = $("#" + form_id);
        var response_target = $("#" + Downcast.plugins.Forms[form_id].response_target);

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




        if (response.success) {

            response_target.html(success_message);
            if (Downcast.plugins.Forms[response.form].reset_on_success) {
               form[0].reset();//resets form after submission 
            }
            if (opt_hide_on_success) {

                if (opt_collapse_on_hide) {
                    form.hide();//this will keep the form in place, so the structure of the page won't shrink when it goes away. if you want it completely removed use '.css('display','none'); or .hide();
                }
                else {
                    form.css('visibility', 'hidden');

                }

            }


        }
        else {

            response_target.html(error_message);

        }

    }



}



DowncastPlugins.Forms.Forms.demo();


