<?php
/**
 * HelloWorld DownCast Plugin
 *
 * This plugin is a starter plugin that demonstrates how to create a plugin. To build your own plugin:
 * Duplicate the entire directory that this plugin resides in
 * rename the directory to your own plugin's unique identifier. Typically two words, both capitalized
 * rename the class and file to match the directory name
 * 
 * 
 * All this file does is to add some text to the sidebar by filtering its content.
 * See getdowncast.com/docs for more information on how to create plugins.
 * 
 * E.g.: for a plugin called 'MyPlugin', you should have a class called 'MyPlugin' in file 'MyPlugin.php' and it should reside in plugins/MyPlugin/MyPlugin.php along with its config.json file.
 * 
 * 
 * 
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * 
 */
class HelloWorld extends DowncastPlugin {

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
