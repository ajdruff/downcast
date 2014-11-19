<?php

/*
 * Tagger DownCast Plugin
 * 
 * 
 * For specific or all urls, replace or Modify the content of any Content or Embed Tag in a Template
 * .
 * 
 * Usage:
 * Add one of the following to the config() method. See Example in comments for more.
 * Remove :
 *   $this->modifyTag( 'SIDE_BAR', '/usage/' );
 * Replace:
 *   $this->modifyTag( 'SIDE_BAR', '/usage/', $different_sidebar );//replace
 * Filter: (adds or modifies its existing content)
 *   $this->modifyTag( 'SIDE_BAR', '/usage/', array( $this, 'filterAddTime' ) ); 
 * 
 * 

 * 
 */

class Tagger extends DowncastPlugin {

    private $_tag_map = null;

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
         * Example 1 - Remove Sidebar from http://example.com/about/
         * 
         * $this->modifyTag( 'SIDE_BAR', '/about/' );//remove is default. to replace, add a 3rd argument
         * 
         */




        /*
         * Example 2 - Modify Navbar for all urls ( using '*' wildcard ) 
         * 

          $different_navbar = "**My New Sidebar** | [Link 1](#) | [Link 2](#)";
          $different_navbar = $this->downcast()->parseMarkdown( $different_navbar );
          $this->modifyTag( 'NAV_BAR', '*' , $different_navbar);
         * 
         */

        /*
         * Example 3- Remove Sidebar just from home page
         * $this->modifyTag( 'SIDE_BAR', '/' );
         */




        /*
         * Example 4 - Add The time and date to the sidebar
         * We'll use a callback , which can be used in place of text 
         * $this->modifyTag( 'SIDE_BAR', '/', array( $this, 'filterAddTime' ) );
         */




    }

    /**
     * Modify Tag
     *
     * Remove or Modify a Tag from a Template
     *
     * @param $url string The url to which the template should be applied
     * @param $template_name string The template name
     * @param $replacement_text string The text to replace the contents of the tag
     * @return void
     */
    public function modifyTag( $tag_name, $url, $replacement_text = '' ) {
        /*
         * Add the Tag to the tag map array
         * we'll then check the array in the filter method
         */
        $this->_tag_map[ $tag_name ][ $url ] = $replacement_text;
        $this->downcast()->addTagFilter( $tag_name, array( $this, 'filterTag' ) );

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
     * Tag Filter
     *
     * Filters the Tag Output 
     *
     * @param $tag_name string The name of the tag to be filtered
     * @param $content string The content of the tag to be filtered
     * @return void
     */
    public function filterTag( $tag_name, $content ) {


        $page_info = $this->downcast()->getPageInfo();
        $url = $page_info[ 'url' ];

        $tag_map = $this->_tag_map;
        if ( !isset( $this->_tag_map[ $tag_name ] ) ){

            return $content;
        }

        /*
         * Search the tag map for the current url 
         * If found, modify the tag
         */

        $array_urls = array_keys( $tag_map[ $tag_name ] );

        if ( in_array( $url, $array_urls ) ) {
            $replacement = $tag_map[ $tag_name ][ $url ];

            /*
             * If value to replace is a callback, use the 
             * result of the callback as the replacement
             */


            if ( is_callable( $replacement ) ) {

                return (call_user_func( $replacement, $tagname, $content ));
} else {

                return $replacement;
}
}

        /*
         * If a wildcard, apply to all urls
         */
        if ( in_array( '*', $array_urls ) ) {
            $replacement = $tag_map[ $tag_name ][ '*' ];

            /*
             * If value to replace is a callback, use the 
             * result of the callback as the replacement
             */

            if ( is_callable( $replacement ) ) {

                return (call_user_func( $replacement, $tagname, $content ));
} else {

                return $replacement;
}

}



        return $content;


}

    /**
     * filter Add Time
     *
     * Modify Content, adding Time to the end of it
     *
     * @param $tagname string The name of the tag
     * @param $content string The content to be filtered
     * @return string The modified content to be returned
     */
    public function filterAddTime( $tagname, $content ) {

        return $content . '<div>' . date( "F j, Y, g:i a" ) . '</div>';
    }


}

?>
