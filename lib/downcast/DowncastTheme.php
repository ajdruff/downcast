<?php

/**
 * DownCast Theme Base Class
 *
 * Applies Custom Theme
 * 
 * Usage: 
 * 
 * use the setTemplateFile() method to map a url to a template:
 *                $this->setTemplateFile(
  '/usage/' // Relative URL
  ,'narrow.php' // Template File Name
  );
 * 
 * Change the default template using 
 * $this->TEMPLATE_FILE_NAME_DEFAULT = "index.php";
 * 
 * All templat
 * 
 * 
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * 
 */
class DowncastTheme extends DowncastPlugin {

    /**
     * Constructor
     *
     * Class Constructor
     *
     * @param $downcast The downcast object so we can reference it from the plugin
     * @return void
     */
    public function __construct( $downcast ){

        parent::__construct( $downcast );
        $this->_config();

}

    private function _config() {

        /*
         * Set default Template file
         */
        $this->TEMPLATE_FILE_NAME_DEFAULT = "index.php";



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
         * Add An Action Hook to Tell DownCast to Call Our Method Before the template is requested
         * 
         * 
         */

        $this->downcast()->addActionHook( 'dc_before_template', array( $this, 'changeTemplate' ) );






    }

    /**
     * Change Template
     *
     * Returns Text (Markdown is not parsed) but with line breaks replaced with br
     *
     * @param none
     * @return $text The parsed Text
     */
    public function changeTemplate() {

        /*
         * set defaults
         * 
         * 
         */

        $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE_BASE_DIRECTORY' ] = dirname( dirname( dirname( __FILE__ ) ) ) . "/plugins/" . get_class( $this ) . "/"; //"plugins/ThemeSeattle";

        /*
         * Templates must go in a directory named 'templates'
         */
        $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE' ] = "templates"; //the parent directory name containing all the templates

        $page_info = $this->downcast()->getPageInfo();


        $url = $page_info[ 'url' ];
        $template_map = $this->TEMPLATE_MAP;

        if ( in_array( $url, array_keys( $template_map ) ) ){


            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE_FILE_NAME' ] = $template_map[ $url ];

        } else {

            $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE_FILE_NAME' ] = $this->TEMPLATE_FILE_NAME_DEFAULT;

        }



}

    /**
     * Set Template File
     *
     * Sets the Template a URL should use
     *
     * @param $url string The url to which the template should be applied
     * @param $template_file_name string The name of the file, including extension, that resides in the templates directory
     * @return void
     */
    public function setTemplateFile( $url, $template_file_name ) {
        $template_map = $this->TEMPLATE_MAP;
        $template_map[ $url ] = $template_file_name;
        $this->TEMPLATE_MAP = $template_map;

    }

}
?>
