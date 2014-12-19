
/**
 * view-source.js
 * 
 * Retrieves the Source of any local link with 'view_source' as a class, and Applies Highlightjs
 * 
 * Please see the readme at /plugins/ViewSource/help/
 * 
 * @author <andrew@nomstock.com>
 * @param none
 * @return none
 */

if (typeof ViewSource=== 'undefined') {
 var ViewSource={};   
}



ViewSource.ViewSource = function() {


    /** 
     * The element clicked by the user
     * 
     * @type {dom} 
     * */
    var clicked_element = null;

    /** 
     * A collection of retrieved source code objects
     * 
     * @type {Object} 
     * 
     * */

    var sources = {};

    /** 
     * The relative file path to 
     * the file whose source code we want
     * serves as the index of {source}
     * 
     * @type {string} 
     * 
     * */
    var source_url;

    /** 
     * The source code object associated
     * with the 'view source' link that we clicked
     * 
     * @type {string} 
     * 
     * */

    var active_source;

    /**
     * Gets the Line Number Button
     * 
     * @param none
     * @return {dom element} The button that toggles line numbers
     */

    function toggleNumbersButton() {
        return $('#' + 'toggleln_' + active_source.id);

    }

    /**
     * Set Source Object
     *
     * Sets the source object's properties after retrieving the source file
     * @param json response The json response from the ajax call 
     * @return object sources
     */

    function setSource(response) {
        var source_index = source_url;
        console.log('source dded, source_index=' + source_index);
        sources[source_index] = {};
        sources[source_index]['rendered_html'] = ($(clicked_element)).attr('data-target')?$('#' + response.id).html() : ''; //($('#' + response.id).length > 0) ? $('#' + response.id).html() : '';
        sources[source_index]['content_element'] = ($(clicked_element)).attr('data-target')?true:false;
        sources[source_index]['file_path'] = response.file_path;
        sources[source_index]['source_url'] = response.source_url;
        sources[source_index]['html'] = response.html;
        sources[source_index]['html_with_line_numbers'] = response.html_with_line_numbers;
        sources[source_index]['id'] = response.id;
        sources[source_index]['show'] = true;
        sources[source_index]['line_numbers'] = false;
        return sources[source_index];
    }

    /**
     * Show Source Code
     *
     * Shows the Source Code associated with the content we are viewing
     * @deprecated
     * @param {source} source The source object associated with the link that was clicked
     * @return none
     */

    function    showSourceCode(source) {

        /*
         * Check first if there is a content container. If there is, replace
         * the rendered content with the source code
         * 
         * If no source container, it means that the link that was clicked was created
         * manually so  place the source code following the number button.
         */

        content_container().html(source.html);
        content_container().highlightjs();


    }

    /**
     * Add Number Button
     *
     * Adds a Button adjacent to the 'show source' link to toggle line numbers
     * @param {dom} el The dom element that was clicked
     * @param {source} source The source object
     * @return void
     */

    function addNumberButton(el, source) {

        $(el).after('&nbsp;&nbsp;<a href="' + source_url + '" class="view_source" id="toggleln_' + source.id + '" href="#">#<!-- Toggle Line Numbers --></a>');
    }
    /**
     * Gets the Toggle Source Button
     * 
     * @param none
     * @return {dom element} The button that toggles the source
     */
    function toggleSourceButton() {
        return $('#' + active_source.id);

    }


    /**
     * Adds Line Numbers to the Source View
     *
     * @param element el The element clicked
     * @return {dom} The button that toggles the source
     */
    function toggleLineNumbers(el) {

        /*
         * Check if the element that was clicked
         * is the link that toggles the line numbers.
         */
        if (($(el).attr('id') === toggleNumbersButton().attr('id'))) {


            /*
             * toggle the current value
             */
            active_source.line_numbers = !active_source.line_numbers;

            /*
             * Based on new value, replace source with/without line numbers
             * and apply highlightjs formatting
             */
            sources[source_url]['line_numbers']
                    ? toggleSourceButton().html(active_source.html_with_line_numbers).highlightjs()
                    : toggleSourceButton().html(active_source.html).highlightjs();
            /*
             * disable number select
             */
            $(".noselect").disableSelection();
            return true;

            /*
             * Return without doing anything 
             * if the button clicked was not a line number button
             */
        } else {

            return false;
        }
    }




    /**
     * View Source Click Event
     * 
     * Shows/Hides Source or Line Numbers, depending on the button
     *
     * @param {dom} The element clicked
     * @return none
     */
    var ViewSourceClickEvent = jQuery('body').on('click', '.view_source', function(e) {

        /*
         * Prevent click through
         */
        e.preventDefault();

        /*
         * save the clicked element 
         */

        clicked_element = this;

        /*
         * save the file path
         * we'll use it as an identifier for source objects
         */
        source_url = $(this).attr('href');



        /*
         * Check if we already have the source
         */

        /*
         * if we already have the source, 
         * either hide source or toggle line numbers
         * 
         */

        if (!sourceObjectIsLoaded()) {
            loadSource();
            return false;
        }


        if (sourceObjectIsLoaded()) {

            active_source = sources[source_url];

            /*
             * 
             * Toggle Line Numbers
             * OR 
             * Toggle Source
             * Depending on what id was clicked
             */
            if (toggleLineNumbers(this)) {
                return false;
            } else {
                toggleViewSource();
                return false;
            }




        }


        /*
         * 
         * Get File Source with Ajax
         * 
         * If we don't yet have the source,
         * then retrieve it with an ajax call
         * 
         * 
         */



        function loadSource() {
            /*
             * Make Ajax Call for source
             */
            $.ajax({
                type: "POST",
                url: "/viewsource/get-source/",
                dataType: "json",
                data: {source_url: source_url},
                success: ajaxResponseHandler
            })

            /**
             * Ajax Response Handler
             *
             * Handles the receipt of the source from the server
             * @param json response The server's json response
             * @return void
             */

            function ajaxResponseHandler(response) {
                {
                    /*
                     * Set Source Object
                     * 
                     * save response elements to source global variable
                     * We can potentially have more than one source on a page
                     * by using the path as an index to the source object
                     */



                    source = setSource(
                            response //the response object
                            );

                    active_source = source;

                    /*
                     * Add a 'Line Number' button with same id except with 'toggleln_' prefix
                     */

                    addNumberButton(
                            clicked_element, //used for positioning
                            active_source //source object, used for setting id
                            );



                    //if container doesnt exist, add it after the number button:
                    if (!elementExists(content_container())) {
                        toggleNumbersButton().after('<div id="' + active_source.id + '"></div>');
                    }

                    //now load the source and highlight it

                    content_container().html(active_source.html);
                    content_container().highlightjs();



                    /*
                     * Add the source after the Line Number button
                     */

                    //  showSourceCode(source);




                }
            }
        }
    });

    /**
     * Source Is Loaded
     *
     * Long
     * @package MintForms
     * @since 0.1.1
     * @uses
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */

    function sourceObjectIsLoaded() {
        return (sources[source_url] !== undefined);
    }
    /**
     * Element Exists
     *
     * Checks if an element Exists
     * @return bool
     */

    function elementExists(el) {
        return el.length > 0;

    }



    /**
     * Content Container
     *
     * Returns the div element that contains the soure code
     * @return dom
     */


    function content_container() {


        return($("#" + active_source.id));
    }

    /**
     * Toggle View Source
     *
     * Shows or Hides the Source, replacing it with the rendered view if available
     * @param none
     * @return void
     */

    function toggleViewSource() {

        //if source is being displayed, then hide it or revert to rendered.
        if (active_source.show === true) {

            if (active_source.content_element) {

                console.log('source is showing, reverting to rendered');
                content_container().html(active_source.rendered_html);
            }
            else {
                console.log('source is showing,hiding it');

                content_container().hide();
            }


            $('#toggleln_' + active_source.id).css('visibility', 'hidden');
            sources[active_source.source_url]['show'] = false;
        } else {
            if (active_source.content_element) {
                console.log('replacing rendered html with source html');
                content_container().html(active_source.html);
            } else {
                console.log('source is hidden,showing it');
                content_container().show();
            }
            $('#toggleln_' + active_source.id).css('visibility', 'visible');
            sources[active_source.source_url]['show'] = true;
        }
        content_container().highlightjs();

    }
}

ViewSource.ViewSource();


