<?php


include ("DowncastBase.php");


/**
 * Downcast 
 * 
 * Manages the Downcast Web Framework
 *

 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @filesource
 */
class Downcast extends DowncastBase   {

    /**
     * Config 
     *
     * Configure - Add User Settings Here
     * @param none
     * @return void
     */

    public function config() {


        /* 
         * SITE_CONFIG_FILE_PATH
         * 
         * Set the Site Configuration File Location
         * Absolute or relative path. 
         * Default: "config.json"
         */
        $this->SITE_CONFIG_FILE_PATH = "config.json"; 
    
        
        

    }
    
    
}



?>