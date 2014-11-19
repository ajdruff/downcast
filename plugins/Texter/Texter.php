<?php

/*
 * Texter DownCast Plugin
 * 
 * 
 * This plugin will not parse the main content of the page, but will parse those tags in the 'Exception' section in config();
 * 
 */

class Texter extends DowncastPlugin {

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
         * Add Exceptions
         * Add a Tag Filter for those ContentTags and EmbedTags we still want parsed
         * i.e.: Navbar, Sidebar, or anything else you need to have parsed
         * 
         * 
         */

        $this->downcast()->addTagFilter( 'SIDE_BAR', array( $this, 'parseMarkdown' ) );

        $this->downcast()->addTagFilter( 'NAV_BAR', array( $this, 'parseMarkdown' ) );




        /*
         * Override parseDown Method
         */

        $override = $this->downcast()->addMethodOverride( 'parseMarkdown', array( $this, 'parserText' ), __CLASS__ );
        if ( $override !== true )
        {
            echo '<br>Plugin error: cannot override method, plugin ' . $override . ' conflicts';

        }


    }

    /**
     * Parse Markdown
     *
     * Parses Markdown
     *
     * @param none
     * @return void
     */
    public function parseMarkdown( $text ) {



        include_once ("lib/bootdown/bootdown.php"); //add the bootdown library here  so we don't run into problems with a globally declared Markdown function with plugins.

        return Markdown( $text );

    }

    /**
     * Parser Disabled
     *
     * Returns Text (Markdown is not parsed) but with line breaks replaced with br
     *
     * @param none
     * @return $text The parsed Text
     */
    public function parserText( $args ) {
        $text = $args[ 'text' ];
        return nl2br( $text );
}

}

?>
