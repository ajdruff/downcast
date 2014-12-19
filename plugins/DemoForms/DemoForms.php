<?php

/**
 * DemoForms DownCast Plugin
 *
 * Adds a set of links or a dropdown that allows you to select a form, then sets a session variable that customizes the skin for the user.
 * Because a session is being used, the change will only impact the single browser session and will be reset when the browser window is closed or cookies are cleared.
 * 
 * Configuration: 
 * $this->DEMO_LINKS_PLACEMENT  changes placement of the links or dropdown. When set to 'custom' , you can use a {SKIN_DEMO} tag anywhere in your template that will show the dropdown or links
 * $this->DEMO_LINKS_TYPE  Specifies whether you want a flat set of links or a dropdown
 * 
 * 
 * Usage: 
 * To add a new skin, you must add it to the $this->SKINS array. The key should be the shortname of the skin that you want to appear in the dropdown or anchor text of demo links, and the value should be the path to the skin
 * 
 * When you want to change the skin, call its url ?skin_demo='/skin/path'
 * E.g.:
 * http://example.com?skin_demo=/bootstrap/2.3.2/amelia
 * 
 * 
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * 
 */
class DemoForms extends DowncastPlugin {

    /**
     * Configure
     *
     * Plugin Configuration
     * Add any code here to set variables and configuration values.
     *
     * @param none
     * @return void
     */
    public function config() {



        $this->DEMO_LINKS_TYPE = 'dropdown'; //'dropdown' or 'links'
        $this->DEMO_LINKS_PLACEMENT = 'sidebar'; //'sidebar','navbar','custom'  //custom enables the {SKIN_DEMO} tag that can be placed anywhere in the template
        $this->DEMO_ID = "forms"; //  <select> attribute id and used as prefix for query variable
        $this->DEMO_DROPDOWN_TEXT = "Select Form Demo"; //text that appears in dropdown box
        $this->DEMO_TAG = "FORMS_DEMO"; //the content tag
        $this->USE_SESSIONS = false;

        $this->downcast()->addPage( '/form/examples/ajax/login-server-validation/', dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login-using-server-side-validation.php' );

        $this->DEMOS = array(

            'Getting Started' => array(
                'url' => '/form/examples/getting-started/',
                'file' => dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started.php'
            ),
            'Getting Started with Validation' => array(
                'url' => '/form/examples/getting-started-with-validation/',
                'file' => dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started-with-server-side-validation.php'
            ),
            'Getting Started with Custom Rule' => array(
                'url' => '/form/examples/getting-started-with-custom-rule/',
                'file' => dirname( __FILE__ ) . '/content/phbc-form-examples/getting-started-with-custom-server-side-rule.php'
            ),
            'Form Elements' => array(
                'url' => '/form/examples/form-elements/',
                'file' => dirname( __FILE__ ) . '/content/phbc-form-examples/form-elements.php'
            ),
            'Ajax' => array(
                'url' => '/form/examples/ajax/',
                'file' => dirname( __FILE__ ) . '/content/phbc-form-examples/ajax.php'
            ),
            'Ajax Login' => array(
                'url' => '/form/examples/ajax/login/',
                'file' => dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login.php'
            ),
            'Ajax Login with Server Validation' => array(
                'url' => '/form/examples/ajax/login-server-validation/',
                'file' => dirname( __FILE__ ) . '/content/phbc-form-examples/ajax-login-using-server-side-validation.php'
            ),
        );




    }

    /**
     * Inititialize
     *
     * Plugin Initialization
     * Add any code here that you want fired when you create plugin and just after configuration.
     *
     * @param none
     * @return void
     */
    public function init() {

        /* Convert $this->Skins to lower case.
         * Ensure all $this->SKINS array keys are lowercase or comparison wont work
         * 
         */
        $this->DEMOS = array_combine( array_map( 'strtolower', array_keys( $this->DEMOS ) ), array_values( $this->DEMOS ) );




        /*
         * Make the default Template the same as configured one
         */
        $this->DEMO_DEFAULT = null; //not needed for forms demo

        switch ( $this->DEMO_LINKS_PLACEMENT ){

            case 'navbar':
                $this->downcast()->addTagFilter(
                        'NAV_BAR', // $tag_name
                        array( $this, 'filterNavbar' ) )
                ;
                break;
            case 'sidebar':
                $this->downcast()->addTagFilter(
                        'SIDE_BAR', // $tag_name
                        array( $this, 'filterSidebar' ) )
                ;
                break;
            case 'custom':
                $this->downcast()->addContentTag( $this->DEMO_TAG, $this->getDemoLinksHtml() );
                break;
            default:
                /*
                 * add a tag so it can be placed anywhere
                 */
                $this->downcast()->addContentTag( $this->DEMO_TAG, $this->getDemoLinksHtml() );

                break;

        }



        /*
         * Add An Action Hook to Tell DownCast to Call Our Method Before the skin is requested
         * 
         * 
         */
        if ( $this->USE_SESSIONS ) {


            $this->downcast()->addActionHook( 'dc_page_info', array( $this, 'demoAction' ) );
            /*
             * start a PHP session so we can read the $_SESSION global variable
             */
            session_start();

        }








    }

    /**
     * Demo action
     *
     * Takes Action to implement demo
     * In this case, sets 
     *
     * @param none
     * @return void
     */
    public function demoAction() {

        /*
         * Update Demo session first
         */
        $this->setSessionVariableToRequestedDemo();

        /*
         * Change SKin
         */

        $this->changeSkin();


    }

    /**
     * Changes Skin
     *
     * Changes Skin Configuration if ?skin_demo is in url and is a valid skin
     *
     * @param none
     * @return void
     */
    public function changeSkin() {
        if ( isset( $_SESSION[ $this->DEMO_ID . '_demo' ] ) ){


            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ] = $_SESSION[ 'skin_demo' ];
} else {


            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ] = $this->DEMO_DEFAULT;

        }

    }

    /**
     * Set Session Variable To Requested Demo
     *
     * Sets a session variable equal to the requested demo
     *
     * @param none
     * @return $text The parsed Text
     */
    public function setSessionVariableToRequestedDemo() {


        /*
         * Get the requested URL and its query string
         */
        $page_info = $this->downcast()->getPageInfo();
        $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ] = (isset( $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ] )) ? strtolower( $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ] ) : null;

        if ( isset( $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ] ) && ( $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ] === 'false') ) {

            unset( $_SESSION[ $this->DEMO_ID . '_demo' ] );
            return;
             }

        /*
         * Check for the '?skin_demo' query variable
         * and a valid skin
         */

        $demo = ((isset( $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ] )) && (in_array( $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ], array_keys( $this->DEMOS ) ))) ? $this->DEMOS[ $page_info[ 'query_vars' ][ $this->DEMO_ID . '_demo' ] ] : null;




        /*
         * Change the Skin if its session variable is not null
         */

        if ( !is_null( $demo ) ){
            $_SESSION[ $this->DEMO_ID . '_demo' ] = $demo;


}









}

    private $_demo_links = null;

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function getDemoLinksHtml() {
        if ( is_null( $this->_demo_links ) ) {
            switch ( $this->DEMO_LINKS_TYPE ){

                case 'links':
                    $demo_links = $this->getLinks();
                    $demo_links = '<div>' . $this->downcast()->parseMarkdown( $demo_links ) . '</div>';
                    break;
                case 'dropdown':
                    $demo_links = $this->getDropdown();
                    break;


        }
            $this->_demo_links = $demo_links;

}


        return $this->_demo_links;
    }

    /**
     * Filter Navbar
     *
     * Filters the Navbar to add our Demo links
     *
     * @param $tag_name string The name of the tag to be filtered
     * @param $content string The content of the tag to be filtered
     * @return void
     */
    public function filterNavbar( $tag_name, $content ) {


        $demo_links = $this->getDemoLinksHtml();



        //  $this->downcast()->debugLog( '$demo_links = ', $demo_links, true, true );



        return $content . $demo_links;
    }

    /**
     * Filter Sidebar
     *
     * Filters the Sidebar to add our Demo links
     *
     * @param $tag_name string The name of the tag to be filtered
     * @param $content string The content of the tag to be filtered
     * @return void
     */
    public function filterSidebar( $tag_name, $content ) {


        $demo_links = $this->getDemoLinksHtml();





        return $content . $demo_links;
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function getLinks() {
        $demo_links = "**Skin Demo Links** | ";
        $demo_links .= "[Reset Skin](?skin_demo=false) | ";

        foreach ( $this->SKINS as $skin_name => $skin_path ) {
            $demo_links .= "[" . ucwords( $skin_name ) . "](?skin_demo=" . urlencode( $skin_name ) . ") | ";
}

        return $demo_links;

    }

    /**
     * Get Dropdown
     *
     * Returns dropdown html for demo links
     *
     * @param none
     * @return void
     */
    public function getDropdown() {
        $demo_links = '<select  id="' . $this->DEMO_ID . '_demo_dropdown">
    <option value="" selected>' . $this->DEMO_DROPDOWN_TEXT . '</option>';

        foreach ( $this->DEMOS as $demo_name => $_url ) {

            /*
             * usually, the demo value will not be an array and just a url
             * if it is an array, take the 'url' element
             */
            $url = (is_array( $_url )) ? $_url[ 'url' ] : $_url;


            if ( $this->USE_SESSIONS ){
                $demo_links .= "<option value=\"?" . $this->DEMO_ID . "_demo=$demo_name\">" . ucwords( $demo_name ) . "</option>";
} else
{

                $demo_links .= "<option value=\"$url\">" . ucwords( $demo_name ) . "</option>";
}


}
        $demo_links .="</select>";
        return $demo_links;
        }
}

?>
