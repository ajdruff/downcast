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
    
    private $_downcast=null;//reference to object that created plugin
    
    /**
     * Downcast Object
     *
     * Returns the Downcast Object so Plugin can access its methods
     * The downcast object is set in the __constructor
     *
     * @param none
     * @return void
     */

    public function downcast( ) {
        
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

        $this->_downcast=$downcast;
        $this->_common_config();
        $this->config();
                $this->_common_init();
        $this->init();

    }
    /**
     * Common Configuration (Internal)
     *
     * Common Configuration
     *
     * @param none
     * @return void
     */
    private function _common_config(  ) {
                /*
                 * Add any configuration that should occur for all plugins here
                 */

    }
        /**
     * Common Initialization (Internal)
     *
     * Common Initialization
     *
     * @param none
     * @return void
     */
    private function _common_init(  ) {
                /*
                 * Add any Initialization that should occur for all plugins here
                 */

    }
    


    

    
}

?>