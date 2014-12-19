<?php
/**
 * SharedScripts DownCast Plugin
 *
 * Add your shared scripts to config.json
 * 
 * 
 * 
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * 
 */
class SharedScripts extends DowncastPlugin {

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
         * Add a tag filter
         */
        $this->downcast()->addTagFilter( 'SIDE_BAR', array( $this, 'filterContent' ) );


    }

    /**
     * Filter Content
     *
     * Demonstration Method to show how to filter the 'CONTENT' tag.
     *
     * @param none
     * @return void
     */
    public function filterContent( $tagname,$content ) {

        return ($content . $this->downcast()->parseMarkdown('* (Added by Hello World Plugin)') );
    }

}

?>
