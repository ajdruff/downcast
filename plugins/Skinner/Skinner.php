<?php

/**
 * Skinner DownCast Plugin
 *
 * Redirect to a different skin based on url
 * 
 * Usage: use the setTemplate() method to map a url to a skin:
 *                $this->setTemplate(
  '/usage/' // Relative URL
  ,'narrow' // Template Name
  );
 * 
 * 
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * 
 */
class Skinner extends DowncastPlugin {

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

        /*
         * Make the default Template the same as configured one
         */
        $this->SKIN_DEFAULT = $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ];


        /*
         * Map Skin to URL 
         * 
         * You must add trailing and leading slashes when there is no file extension in the url request.
         */
        $this->setSkin(
                '/usage/' // Relative URL 
                , '/bootstrap/2.3.2/journal' // Skins
        );

        $this->setSkin(
                '/' // Relative URL 
                , '/bootstrap/2.3.2/spruce' // Skins
        );

        $this->setSkin(
                '/about/' // Relative URL 
                , '/bootstrap/2.3.2/flatly' // Skins
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

        /*
         * Add An Action Hook to Tell DownCast to Call Our Method Before the skin is requested
         * 
         * 
         */

        $this->downcast()->addActionHook( 'dc_content', array( $this, 'changeSkin' ) );




        

    }

    /**
     * Change Skin
     *
     * Returns Text (Markdown is not parsed) but with line breaks replaced with br
     *
     * @param none
     * @return $text The parsed Text
     */
    public function changeSkin() {
  

        $page_info = $this->downcast()->getPageInfo();


        $url = $page_info['url'];
        
        $skin_map = $this->SKIN_MAP;

        $this->downcast()->debugLog( '$url = ', $url, true, false );


        if ( in_array( $url, array_keys( $skin_map ) ) ){

 $this->downcast()->debugLog( 'changed skin', '', true, false );

            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ] = $skin_map[ $url ];
  
        } else {

            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ] = $this->SKIN_DEFAULT;

        }



       
       
       
}

    /**
     * Set Skin
     *
     * Sets the Template a URL should use
     *
     * @param $url string The url to which the skin should be applied
     * @param $skin_name string The skin name
     * @return void
     */
    public function setSkin( $url, $skin_name ) {
        $skin_map = $this->SKIN_MAP;
        $skin_map[ $url ] = $skin_name;
        $this->SKIN_MAP = $skin_map;

    }

}

?>
