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
class ThemeSeattle extends DowncastTheme {

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
         * Map Templates to URL 
         * 
         * You must add trailing and leading slashes when there is no file extension in the url request.
         */
        $this->setTemplateFile(
                '/usage/' // Relative URL 
                , 'narrow.php' // Template File Name
        );




    }


}

?>
