window.onload = function() //onload needed since we are loading jquery at end
{
    $(function() {
        // bind change event to select
        $("#forms_demo_dropdown").on("change", function() {
            var url = $(this).val(); // get selected value
            if (url) { // require a URL
                window.location = url; // redirect
            }
            return false;
        });
    });
}