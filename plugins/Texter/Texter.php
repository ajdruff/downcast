<?php
/**
 * Texter DownCast Plugin
 *
 * This plugin will not parse the main content of the page, but will parse those tags in the 'Exception' section in config();
 * It does this by overriding the main parser method of the Downcast class to only return the text unparsed. At the same time, it adds a tagfilter  for each tag that does need to be parsed, and parses the content of those tags when they are filtered.
 * 
 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
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

        $this->downcast()->addTagFilter( 'SIDE_BAR', array( $this, 'tagfilterParseMarkdown' ) );

 





        $this->downcast()->addTagFilter( 'NAV_BAR', array( $this, 'tagfilterParseMarkdown' ) );




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
     * Tag Filter - Parse Markdown
     *
     * Parses Markdown for excluded tags
     *
     * @param none
     * @return void
     */
    public function tagfilterParseMarkdown( $tagname,$content ) {



        include_once ("lib/bootdown/bootdown.php"); //add the bootdown library here  so we don't run into problems with a globally declared Markdown function with plugins.

        return Markdown( $content );

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
