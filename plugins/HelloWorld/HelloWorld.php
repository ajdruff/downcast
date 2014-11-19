<?php

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
