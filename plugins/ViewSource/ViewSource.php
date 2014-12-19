<?php

/*
 * ViewSource DownCast Plugin
 * 
 * 
 * Adds a 'View Source' tag beside a content element,that when clicked shows
 * the source in a code block.
 * 
 * .
 * 
 * Usage:
 * Browse to the help documentation for this plugin by going to 
 * 'http://<your-web-site-domain>/plugins/<PluginName>/help/' 
 * or by reading the readme.md contained in the root folder of this plugin
 * 
 * 

 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * 
 */

class ViewSource extends DowncastPlugin {

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
         * Configure Layout
         * 
         * Required Templates
         * $this->VIEW_SOURCE_LINK_TEMPLATE  The link html. Use the {VIEW_SOURCE_TEXT} and {RELATIVE_PATH}
         * $this->VIEW_SOURCE_CONTENT_TEMPLATE
         * Required Tags:
         * {RELATIVE_PATH} The relative file path to the page content or element. href of links must be set to this
         * {VIEW_SOURCE_TEXT}The anchor text
         * {VIEW_SOURCE_LINK} The hyperlink that triggers showing/hiding source
         * {CONTENT} The content of the page or page element
         * 
         * Example 
         * 
         * The following settings will place a 'Toggle Source' link , formatted as a label, at the top of each content element.
         * 
          $this->VIEW_SOURCE_TEXT = 'Toggle Source';
          $this->VIEW_SOURCE_LINK_TEMPLATE = '<a class="view_source label" id="link_{ID}" href="{RELATIVE_PATH}">{VIEW_SOURCE_TEXT}</a>';
          $this->VIEW_SOURCE_CONTENT_TEMPLATE = '{CONTENT}{VIEW_SOURCE_LINK}';
         * 
         * Troubleshooting:
         * Make sure that 'view_source' is added as a class to your VIEW_SOURCE_LINK_TEMPLATE. Without this class, the javascript will not detect a click
         * link's id must be included and be in the form id="link_{ID}"
         * 
         */
        $this->VIEW_SOURCE_TEXT = 'src';
        $this->VIEW_SOURCE_LINK_TEMPLATE = '<a class="view_source label"  title="view source@{RELATIVE_PATH}" href="{RELATIVE_PATH}">{VIEW_SOURCE_TEXT}</a>';
        $this->VIEW_SOURCE_CONTENT_TEMPLATE = '{CONTENT}{VIEW_SOURCE_LINK}';

        /*
         * Content Element Source Links
         * true to add a link near each content element to view its source
         * false to not add it.
         * Default:false
         */

        $this->REVEAL_ALL = false;


        /*
         * Don't edit below this line
         */

        $this->downcast()->addPage( '/viewsource/get-source/', dirname( __FILE__ ) . '/content/get-source.php' );
        $this->downcast()->addPage( '/viewsource/demo/', dirname( __FILE__ ) . '/content/view-source-demo.php' );

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

        if ( $this->REVEAL_ALL ){

            /*
             * add an empty content tag so we don't add 2 links 
             * since the VIEWSOURCE tag should work only if the CONTENT_ELEMENT SOURCE LINKS are disabled

             */
            $this->downcast()->addContentTag( 'VIEW_SOURCE', '' );
        }

        $this->downcast()->addFilterHook( 'dc_embed_tag', array( $this, 'filterEmbedTag' ) );

    }

    /**
     * Filter - Embed Tag
     *
     * Filters the Embed Tag
     *
     * @param string $content The rendered content of the embed tag
     * @param $args array The arguments passed by the doFilterHooks method
     * @return void
     */
    public function filterEmbedTag( $content, $args ) {

        $tag_name = $args[ 'tag_name' ];
        $file_path = $args[ 'file_path' ];

        $relative_path = $this->downcast()->file_getRelativePath( $file_path );
        $id = $this->convertToDomID( $relative_path );



        $tags[ 'RELATIVE_PATH' ] = $relative_path;
        $tags[ 'VIEW_SOURCE_TEXT' ] = $this->VIEW_SOURCE_TEXT; //the text that appears in the 'view source' link
        $this->VIEW_SOURCE_LINK_TEMPLATE = str_replace( 'href', ' data-target="' . $id . '" href', $this->VIEW_SOURCE_LINK_TEMPLATE );
        $view_source_link = $this->downcast()->crunchTpl( $tags, $this->VIEW_SOURCE_LINK_TEMPLATE );

        unset( $tags );
        $tags[ 'VIEW_SOURCE_LINK' ] = $view_source_link;

        $tags[ 'ID' ] = $id;
        /*
         * Add the Link only if content element source links are enabled
         * OR
         * there is a {VIEW_SOURCE} tag in the content
         */
               if ( ($this->REVEAL_ALL )||( stripos($content,'{VIEW_SOURCE}' )!==false )){
             $content=str_replace('{VIEW_SOURCE}','',$content);
            

             $content = '<div id="' . $tags[ 'ID' ] . '">' . $content . '</div>';
        $tags[ 'CONTENT' ] = $content;
        $content = $this->downcast()->crunchTpl( $tags, $this->VIEW_SOURCE_CONTENT_TEMPLATE );
              
   }



        return $content;

}

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function convertToDomID( $text ) {
        //text.replace(/\W+/g, " ");
        /*
         * replace slashes and dashes
         * with underscores
         */
        $text = str_replace( array( '/', '\\', '-' ), '_', $text );
        /*
         * replace non-alpha at startr of string
         */
        $text = preg_replace( '~^[^\da-z]+~i', '', $text );

        /*
         * remove all remaining non-alphanumeric except underscores
         */
        return(preg_replace( '/\W+/', "", $text ));



    }


}

?>
