<?php

/**
 * Downcast Plugin Base 
 * 
 * Plugin Base Class
 *

 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @filesource
 */
class DowncastPlugin   {

    private $_downcast = null; //reference to object that created plugin

    /**
     * Downcast Object
     *
     * Returns the Downcast Object so Plugin can access its methods
     * The downcast object is set in the __constructor
     *
     * @param none
     * @return void
     */

    public function downcast() {

        return $this->_downcast;

    }

    /**
     * Constructor
     *
     * Class Constructor
     *
     * @param $content boolean Flags whether the instance is being used to parse content (true) or the main template file (false). 
     * @return void
     */
    public function __construct( $downcast ){

        $this->_downcast = $downcast; //make the $downcast object available to the plugin
        $downcast->readConfigFile( "plugins/" . get_class( $this ) . "/" . "config.json" ); //read config file
        $this->_common_config();
        $this->config();
        $this->_addTags();

    }

    /**
     * Add Tags
     *
     * Adds Plugin Embed and Content Tags
     *
     * @param none
     * @return void
     */
    private function _addTags() {
      
        
        $plugin_id = get_class( $this );
        
        
                   /*
         * Add Content Tags
         */
 
        $this->downcast()->addContentTags( $this->downcast()->CONFIG[ 'PLUGIN' ][ $plugin_id ][ 'CONTENT_TAGS' ] );

        /*
         * Add Embed Tags
         */

        $this->downcast()->addEmbedTags( $this->downcast()->CONFIG[ 'PLUGIN' ][ $plugin_id ][ 'EMBED_TAGS' ] );
       
        




}

    /**
     * Plugin Initialization ( Must be public so Base can reach it) 
     *
     * Pubilic Initalization
     *
     * @param none
     * @return void
     */
    public function _plugin_init() {

        $this->_common_init(); //common internal initialization
        $this->init(); //user's initialization

    }

    /**
     * Common Configuration (Internal)
     *
     * Common Configuration
     *
     * @param none
     * @return void
     */
    private function _common_config() {
        /*
         * Add any configuration that should occur for all plugins here
         */

/*
 * add help page
 */
        $this->downcast()->addPage( '/plugins/'.get_class($this).'/help/', dirname(dirname(dirname( __FILE__ ))) . '/plugins/'.get_class($this).'/readme.md' );
        

    }

    /**
     * Common Initialization (Internal)
     *
     * Common Initialization
     *
     * @param none
     * @return void
     */
    private function _common_init() {
        /*
         * Add any Initialization that should occur for all plugins here
         */

}






}

?>