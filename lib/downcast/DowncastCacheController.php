<?php

class DowncastCacheController{
    /*
     * Default Cache Settings
     * 
     * $_cleanup - If set to true, will delete all cache files.
     * $_timeout - Time, in seconds, before a page's cache will time out
     * $_path - Absolute directory path where cache will be created. Must have 0777 permissions and contain a directory called cache.storage. If not set, will attempt to use the web server's temp directory'
     * 
     * 
     * To Override these settings, edit the config-cache.json file. s
     * 
     */
    
    private static $_enabled = true;
    private static $_cleanup = false;
    private static $_timeout = 600;
    private static $_path = null;
    private static $_debug = false;

    
    
    /**
     * Is Enabled
     *
     * Returns whether the cache is enabled
     *
     * @param none
     * @return bool True if enabled, False if disabled
     */
    public static function isEnabled( ) {
            return self::$_enabled;

    }
    
    /**
     * Sets up Cache Configuration
     *
     * Configures Cache Settings from Configuration File or defaults
     *
     * @param none
     * @return void
     */
    public static function setup() {
        /*
         * 
         * Check for Cache Configuration File
         * Otherwise, use hardcoded values
         * 
         */

        $config = json_decode( file_get_contents( "config-cache.json" ), true );


        /*
         * 
         * Configure, using defaults if not set
         * 
         */
        self::$_enabled = isset( $config[ 'CACHE' ][ 'CONFIG' ][ 'ENABLED' ] ) ? $config[ 'CACHE' ][ 'CONFIG' ][ 'ENABLED' ] : self::$_enabled;
        self::$_cleanup = (isset( $config[ 'CACHE' ][ 'CONFIG' ][ 'CLEANUP' ] )) ? $config[ 'CACHE' ][ 'CONFIG' ][ 'CLEANUP' ] : self::$_cleanup;
        self::$_timeout = (isset( $config[ 'CACHE' ][ 'CONFIG' ][ 'TIMEOUT' ] )) ? $config[ 'CACHE' ][ 'CONFIG' ][ 'TIMEOUT' ] : self::$_timeout;
        self::$_path = (isset( $config[ 'CACHE' ][ 'CONFIG' ][ 'PATH' ] )) ? $config[ 'CACHE' ][ 'CONFIG' ][ 'PATH' ] : self::$_path;
        self::$_debug = (isset( $config[ 'CACHE' ][ 'CONFIG' ][ 'DEBUG' ] )) ? $config[ 'CACHE' ][ 'CONFIG' ][ 'DEBUG' ] : self::$_debug;




    }

    /**
     * Get Page From Cache
     *
     * Get Page From Cache
     *
     * @param none
     * @return void
     */
    public static function getPageFromCache() {



        /*
         * Get PhpFastCache Library
         * http://www.phpfastcache.com/
         * 
         */
        include("lib/phpfastcache/php_fast_cache.php");

        /*
         * Set phpFastCache Cache Engine
         * 
         */

        phpFastCache::$storage = "files";


        /*
         * Set phpFastCache Cache Directory
         * 
         */
        if ( !is_null( self::$_path ) || (self::$_path !== '') ) {
            phpFastCache::$path = self::$_path;
}



        /*
         * Cleanup Cache 
         */

        if ( self::$_cleanup ){
            phpFastCache::cleanup();
        }

        /*
         * Display the Page
         */
        self::_showPage();



    }

    private static $_page_hash = null;

    /**
     * Get Page Hash
     *
     * Returns a hash of the current URL. 
     * This is used as an index to find the page in cache
     *
     * @param none
     * @return string The hash of the URL
     */
    private static function _getPageHash() {
        if ( is_null( self::$_page_hash ) ) {
            self::$_page_hash = md5( $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] . $_SERVER[ 'QUERY_STRING' ] );

}
        return (self::$_page_hash);

    }

    /**
     * Show Page
     *
     * If the page is in cache, echos it to browser
     * If the page isn't in cache, calls getNewPage() to generate a new page and caches it
     *
     * @param none
     * @return void
     */
    private static function _showPage() {


        /*
         * Get the Page from HTML
         */
        $html_from_cache = phpFastCache::get( self::_getPageHash() );

        /*
         * Show Debug Info
         */
        if ( self::$_debug ) {

            echo '<br> ### DownCast Debugging Info #####<br>';
            phpFastCache::$debugging = true;
            $debug_cache_cleanup = (self::$_cleanup) ? ' True' : ' False';
            echo '<br><br><br> CACHE_CLEANUP = ' . $debug_cache_cleanup;
            echo '<br> CACHE_TIMEOUT = ' . self::$_timeout . ' seconds';
            echo '<br> CACHE_PATH = ' . self::$_path;
            echo '<br>  phpFastCache::$path = ' . phpFastCache::$path;
            echo '<br>phpFastCache System Info<pre>', print_r( phpFastCache::systemInfo(), true ), '</pre>';




}



        /*
         * 
         * If the page was cached, echo it to the browser
         * 
         */
        if ( $html_from_cache !== null ) {
            if ( self::$_debug ){


                $cache_file = phpFastCache::$path . '/cache.storage/files/' . self::_getPageHash() . '.c.html';
                echo ' <br> Cache file is ' . $cache_file;
                $date = new DateTime( "@" . filemtime( $cache_file ) ); //ref:http://php.net/manual/en/datetime.settimestamp.php , example 2.

                echo '<br> Page Generated on ' . $date->format( 'Y-m-d H:i:s' );


}
            /*
             * Echo Cached File
             */

            echo $html_from_cache;
            return;


        }


        /*
         * If Not In Cache
         * Capture a New Page to Buffer
         */

        ob_start();
        self::getNewPage();
        $html_new_page = ob_get_clean();

        /*
         * Output Buffer to screen
         */

        echo $html_new_page;

        /*
         *  Save to Cache XX minutes
         */
        phpFastCache::set( array( "files" => self::_getPageHash() ), $html_new_page, self::$_timeout );



}

    /**
     * Get New Page
     *
     * Returns the HTML for a new page
     *
     * @param none
     * @return void
     */
    public static function getNewPage() {


        /*
         * 
         * Include the Downcast Library
         * 
         */
        include("lib/downcast/Downcast.php");
        include("lib/downcast/DowncastPlugin.php");


        /*
         * Create a new Downcast Object
         */
        $io = new Downcast( false );
      
        $io->doActionHooks( 'dc_before_template' );

        /*
         * 
         * Get the Template
         * 
         */
 
        $io->renderTemplate( "templates/" . strtolower( $io->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE' ] ) . "/index.php" );
        $io->doActionHooks( 'dc_after_template' );
        $io->doActionHooks( 'dc_controller_end' );




    }
}
?>