<?php

/**
 * SkinDemo DownCast Plugin
 *
 * Adds a set of links or a dropdown that allows you to select a skin, then sets a session variable that customizes the skin for the user.
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
class SkinDemo extends DowncastPlugin {

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

        $this->SKINS = array(
            'amelia' => '/bootstrap/2.3.2/amelia',
            'cerulean' => '/bootstrap/2.3.2/cerulean',
            'Cyborg' => '/bootstrap/2.3.2/cyborg',
            'cosmo' => '/bootstrap/2.3.2/cosmo',
            'default' => '/bootstrap/2.3.2/default',
            'flatly' => '/bootstrap/2.3.2/flatly',
            'journal' => '/bootstrap/2.3.2/journal',
            'readable' => '/bootstrap/2.3.2/readable',
            'simplex' => '/bootstrap/2.3.2/simplex',
            'slate' => '/bootstrap/2.3.2/slate',
            'spacelab' => '/bootstrap/2.3.2/spacelab',
            'spruce' => '/bootstrap/2.3.2/spruce',
            'superhero' => '/bootstrap/2.3.2/superhero',
            'superhero With HighlightJS' => array( '/bootstrap/2.3.2/superhero', "/highlightjs/8.3" )
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
        $this->SKINS = array_combine( array_map( 'strtolower', array_keys( $this->SKINS ) ), array_values( $this->SKINS ) );




        /*
         * Make the default Template the same as configured one
         */
        $this->SKIN_DEFAULT = $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ];

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
                $this->downcast()->addContentTag( 'SKIN_DEMO', $this->getDemoLinksHtml() );
                break;
            default:
                /*
                 * add a tag so it can be placed anywhere
                 */
                $this->downcast()->addContentTag( 'SKIN_DEMO', $this->getDemoLinksHtml() );

                break;

        }



        /*
         * Add An Action Hook to Tell DownCast to Call Our Method Before the skin is requested
         * 
         * 
         */

        $this->downcast()->addActionHook( 'dc_page_info', array( $this, 'changeSkin' ) );

        /*
         * start a PHP session so we can read the $_SESSION global variable
         */
        session_start();







    }

    /**
     * Change Skin
     *
     * Changes Skin Configuration if ?skin_demo is in url and is a valid skin
     *
     * @param none
     * @return $text The parsed Text
     */
    public function changeSkin() {


        /*
         * Get the requested URL and its query string
         */
        $page_info = $this->downcast()->getPageInfo();
        $page_info[ 'query_vars' ][ 'skin_demo' ] = (isset( $page_info[ 'query_vars' ][ 'skin_demo' ] )) ? strtolower( $page_info[ 'query_vars' ][ 'skin_demo' ] ) : null;

        if ( isset( $page_info[ 'query_vars' ][ 'skin_demo' ] ) && ( $page_info[ 'query_vars' ][ 'skin_demo' ] === 'false') ) {

            unset( $_SESSION[ 'skin_demo' ] );
            return;
             }

        /*
         * Check for the '?skin_demo' query variable
         * and a valid skin
         */

        $skin = ((isset( $page_info[ 'query_vars' ][ 'skin_demo' ] )) && (in_array( $page_info[ 'query_vars' ][ 'skin_demo' ], array_keys( $this->SKINS ) ))) ? $this->SKINS[ $page_info[ 'query_vars' ][ 'skin_demo' ] ] : null;




        /*
         * Change the Skin if its session variable is not null
         */

        if ( !is_null( $skin ) ){
            $_SESSION[ 'skin_demo' ] = $skin;


}




        if ( isset( $_SESSION[ 'skin_demo' ] ) ){


            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ] = $_SESSION[ 'skin_demo' ];
} else {


            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ] = $this->SKIN_DEFAULT;

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
        $demo_links = '<select  id="skin_demo_dropdown">
    <option value="" selected>Switch Skin</option>';
        $demo_links .= "<option value=\"?skin_demo=false\">Reset</option>";
        foreach ( $this->SKINS as $skin_name => $skin_path ) {

            $demo_links .= "<option value=\"?skin_demo=$skin_name\">" . ucwords( $skin_name ) . "</option>";
}
        $demo_links .="</select>";
        return $demo_links;
        }
}
?>
