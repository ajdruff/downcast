<?php

/**
 * ThemeSeattle DownCast Theme Plugin
 *
 * Overrides all Skin and Template Settings and applies custom theme
 * 
 * Usage: use the setTemplate() method to map a url to a template:
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
class ThemeSeattle extends DowncastPlugin {

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
        $this->TEMPLATE_FILE_NAME_DEFAULT = "index.php";


        /*
         * Map Templates to URL 
         * 
         * You must add trailing and leading slashes when there is no file extension in the url request.
         */
        $this->setTemplate(
                '/usage/' // Relative URL 
                , 'narrow.php' // Template File Name
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

        $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE_BASE_DIRECTORY' ] =dirname(__FILE__);//"plugins/ThemeSeattle";
        $this->downcast()->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE' ] ="templates";//the parent directory name containing all the templates
        
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
     * Set Template Path
     *
     * Sets the Template a URL should use
     *
     * @param $url string The url to which the template should be applied
     * @param $template_name string The template name
     * @return void
     */
    public function setTemplate( $url, $template_name ) {
        $template_map = $this->TEMPLATE_MAP;
        $template_map[ $url ] = $template_name;
        $this->TEMPLATE_MAP = $template_map;

    }

}

?>
