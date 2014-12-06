<?php

/**
 * ExampleAddContent DownCast Plugin
 *
 * This is example plugin code that shows you how to add content for a specific url.
 * Create the file, then map the url to the file by using $this->downcast()->addPage();
 * This is really just a simplified version of the Tagger plugin, which can also be used for the same purpose.
 * This plugin is not intended to be used alone - developers should instead use this as an example of how to add urls that point to their content.
 * 
 * 
 * Usage:
 * in config() method, tell the plugin to map the content of your file 'example-content.md' to the url /my_plugin_content/
 * 
 *  $this->addContent( '/my_plugin_content/',$this->getContent('example-content.md') );//remove is default. to replace, add a 3rd argument

 * Be sure to add the file 'example-content.md' to the content directory within your plugin's main directory. 
 * 
 * 
 * 
 * 
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * 
 */
class ExampleAddContent extends DowncastPlugin {


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
         * Add a HTML, Markdown, or PHP file.
         *  
         * 
         * 
         */

$this->downcast()->addPage('/my-markdown-file/',  dirname( __FILE__ ) . '/content/' . 'example-content.md' );
$this->downcast()->addPage('/hello-world/',  dirname( __FILE__ ) . '/content/' . 'hello-world.php' );
$this->downcast()->addPage('/php-info/',  dirname( __FILE__ ) . '/content/' . 'php-info.php' );
$this->downcast()->addPage('/plugins/my-plugin/help/',  dirname( __FILE__ ) . '/content/' . '/help/plugin-help.html' );


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



    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function helloWorld( ) {
              echo 'how are ya?';

    }

    
  
    
    
    }

?>
