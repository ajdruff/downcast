<?php

/**
 * DowncastBase (Parent Class)
 * 
 * Manages the Downcast Web Framework
 *
 * @property string $CSS_BASE_DIR The class namespace used for the Addons, e.g.: 'Simpli_Addons'


 * @package Downcast
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012 Andrew Druffner
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @filesource
 */
class DowncastBase   {

    protected $_content = null;

    /**
     * Constructor
     *
     * Class Constructor
     * 
     * Please note that the order of method calls in the constructor is 
     * important and defines the order of precedence for which overrides what.
     * Tags defined first override tags defined later for instance.
     * Plugins need access to site configuration
     *
     * @param $is_content boolean Flags whether the instance is being used to parse content (true) or the main template file (false). 
     * @return void
     */
    public function __construct( $is_content = false ){



        $this->doActionHooks( 'dc_controller_start' );

        $this->is_content = $is_content;



        $this->_config(); //internal defaults

        $this->config(); //user configuration and Tags

        /*
         * Read config.json files
         */
        $this->_loadSiteConfigFile();



        $this->_loadPlugins(); //reads plugin configuration,adds plugin tags, creates plugin objects and assigns to $this->plugins()
        $this->doActionHooks( 'dc_plugins_loaded' );
        $this->_initPlugins(); //calls the init() method for each plugin
        $this->doActionHooks( 'dc_plugins_initialized' );






        /*
         * Must Add {CONTENT} Embed Tag 
         * Path is always uri when getting content of the page
         * 
         */

        $this->addEmbedTag( 'CONTENT', $_SERVER[ 'REQUEST_URI' ] );
        $this->doActionHooks( 'dc_content' );//content loaded, page_info determined

        $this->_loadTemplateConfigFile();
        $this->_loadSkinConfigFile();
        $this->debugLog( '$this->CONFIG = ', $this->CONFIG, true, true );

        $this->doActionHooks( 'dc_configuation_complete' );

        $this->_addTags(); //add site and template content and embed tags
        
        
        $this->doActionHooks( 'dc_resources_start' );
        $this->_addCssAndJsTags(); //define CSS and Javascript Template Tags
        $this->doActionHooks( 'dc_resources_end' );





        $this->_init();

    }

    private $_page_info = null;

    /**
     * Config (internal)
     *
     * Configure Plugin - Use this to set defaults. Some defaults are not set here though ( like Site Config settings - see _addMissingSiteConfig() )
     * @param none
     * @return void
     */
    private function _config() {
        /*
         * Root Directory Path
         * This is the path to the application's root directory (not CONTENT_ROOT);
         * assumes this file resides in /lib/downcast/
         * Use $this->getAbsPath() to retrieve its value 
         */
        $this->setRootDirectory( dirname( dirname( dirname( __FILE__ ) ) ) );


        /*
         * Debug
         */
        $this->DEBUG = FALSE;
        $this->DEBUG_SHOW_ERRORS = TRUE;
        $this->DEBUG_FILE = $this->file_joinPaths( $this->getRootDirectory(), '/downcast-error.log' );


        $this->STRING_DEBUG_LABEL = 'DownCast Error:';
        $this->DEBUG_LOG_TO_FILE = TRUE;

        /*
         * Set the Site Configuration File Location
         * Absolute or relative path. 
         * Default: "config.json"
         */
        $this->SITE_CONFIG_FILE_PATH = "config.json";





}

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function config() {
        
    }

    /**
     * Init
     *
     * Post Configuration Initialization
     *
     * @param none
     * @return void
     */
    private function _init() {

        /*
         * Add Plugins
         * Need to add plugins first before tags are added or filters wont work
         */
        //$this->_addPlugin( 'HelloWorld' );
        //$this->_addPlugin( 'Parsedowner' );
        // $this->_addPlugin( 'Parsedowner' );
        // $this->_addPlugin( 'JustText' );
        //$this->_addPlugin( 'Templater' );
        // $this->_addPlugin( 'Tagger' );
        /*
         * Add Site Configuration
         */












        /*
         * Add Powered By Tag
         */
        $this->addContentTag( 'POWERED_BY', '<a href="#"><span class="label">Powered by DownCast &reg;</span></a>' );
        $this->addContentTag( 'PAGE_GENERATION_TIME', 'Page Generation Time: ' . date( 'Y-m-d H:i:s' ) );



    }

    /**
     * Returns Page Info
     *
     * Returns the scrubbed version of the page url, file path, query string,etc.
     *
     * @param none
     * @return array An array of the url and file parts
     */
    public function getPageInfo() {

        if ( is_null( $this->_page_info ) ) {
            $this->_page_info = array();
}
        return $this->_page_info;

        }

    /**
     * Add Tag
     *
     * Adds a Content Tags to the Content Tags Configuration Setting
     *
     * @param none
     * @return void
     */
    public function addContentTag( $tag_name, $value ) {

        $tags = $this->_config_properties[ 'CONTENT_TAGS' ];
        $tags[ $tag_name ] = $value;
        $this->setConfig( 'CONTENT_TAGS', $tags );

    }

    /**
     * Add Template Tag
     *
     * Adds a Template Tags to the Template Tags Configuration Setting
     *
     * @param none
     * @return void
     */
    public function addEmbedTag( $tag_name, $file_path ) {
//only add template tags if we are not parsing content or we'll end up in a loop


        if ( !$this->is_content ){

            $tags = $this->_config_properties[ 'EMBED_TAGS' ];
            /*
             * Add a Tag only If one Doesn't Already Exist
             * This means that settings added by the user in Downcast.php will
             * override those that are added in config.php of the theme.
             * It also means that the user cannot override the settings created in _config()
             */



            if ( !isset( $tags[ $tag_name ] ) ){

                $tags[ $tag_name ] = $this->renderPage( $file_path, false );

        }
            $this->setConfig( 'EMBED_TAGS', $tags );


}

}

    /**
     * Render Page
     *
     * Renders a markdown page passed to it if markdown, otherwise serves it. 
     *
     * @param $file_path string The relative path to
     * @return void
     */
    public function renderPage( $url, $echo = true ) {



        //initialize
        $file = array();
        $markdown = false;
        $url = $this->addLeadingSlash( $url );

        /*
         * Resolve Url To a File
         * 
         */


        $file = $this->getFileFromUrl( $url );













        /*
         * Render File If Exists
         * 
         */



        if ( $file !== false ){ //if the file exists
            switch ( $file[ 'extension' ] ) {
                case 'txt':
                case 'markdown':
                case 'md':





                    $markdown = true;

                    break;
                case 'php':
                case 'html':
                case 'htm':

                    $markdown = false;
                    break;

                default:
                    $markdown = false;
                    break;


                }

            if ( $echo )
        {
                if ( $markdown ){

                    echo ( $this->parseMarkdown( ($this->renderTemplate( $file[ 'path' ], false ) ) ));
                        } else {

                    echo ( ( ($this->renderTemplate( $file[ 'path' ], false ) ) ));
                        }

                        } else
            {
                if ( $markdown ){

                    return ( $this->parseMarkdown( ($this->renderTemplate( $file[ 'path' ], false ) ) ));
                             } else {
                    return ( ( ($this->renderTemplate( $file[ 'path' ], false ) ) ));

                             }
            }




            } else {

            /*
             * Generate 404 if file is not found
             * We check first to see if the REQUEST_URI matches the url argument
             * If not, its an included file and we don't want to return a 404 for it, but
             * we'll still generate a 'page not found' page
             */

            if ( $url === $_SERVER[ 'REQUEST_URI' ] ) {


                header( "HTTP/1.0 404 Not Found" );

              }
            return ( $this->parseMarkdown( ($this->renderPage( "errors/404/index.md?page_not_found=" . $url, false ) ) ));




            }



        }

    /**
     * Add Leading Slash
     *
     * Adds a Leading Slash If one doesn't already exist
     *
     * @param $path string The path to the file
     * @return string The path with a leading slash
     */
    public function addLeadingSlash( $path ) {


        $result = '/' . ltrim( $path, '/' );

        return $result;

    }

    /**
     * Add Trailing Slash
     *
     * Adds a Trailing Slash If one doesn't already exist
     *
     * @param $path string The path to the file
     * @return string The path with a leading slash
     */
    public function addTrailingSlash( $path ) {

        $path = rtrim( $path, '/' );
        return $path . '/';

    }

    /**
     * Get Configuration (Magic Method)
     *
     * Return 'read only' properties using the $this->Property format.
     * You *can* add or edit these values by using the protected method $this->setConfig().
     * Returns read-only properties using a magic method __get
     * ref: http://stackoverflow.com/questions/2343790/how-to-implement-a-read-only-member-variable-in-php
     * @param none
     * @return void
     */
    public function __get( $property_name ) {


        $properties = $this->_getProperties();

        if ( isset( $properties[ $property_name ] ) ) {
            $config_value = $properties[ $property_name ];
        } else {

            $config_value = null;
        }

        return $config_value;
    }

    protected $_config_properties = null;

    /**
     * Get Configs
     *
     * Returns the properties array
     * @param none
     * @return array
     */
    private function _getProperties() {

        if ( is_null( $this->_config_properties ) ) {
            $this->_config_properties = array();
        }
        return $this->_config_properties;
    }

    /**
     * Get Template
     *
     * Returns the template as a string, uses cache if already accessed
     *
     * @param string $template_name The base name of the template file, without the extension
     * @return string
     */
    private $_template_cache = array();

    public function getTemplate( $template_path ) {


        if ( !isset( $this->_template_cache[ $template_path ] ) ) {

            if ( !file_exists( $template_path ) ) { //if file doesn't exist, try acccessing it without the tpl extension
                return null;

            }


            ob_start();


            include($template_path);
            $template = ob_get_clean();
            $this->_template_cache[ $template_path ] = $template;
        }

        return $this->_template_cache[ $template_path ];
    }

    /**
     *  getRenderedFileContents()  - Get Rendered File Contents
     * @replaces renderTemplate
     *
     * Returns the parsed contents of a file.
     * 
     * Usage: Like file_get_contents , nothing rendered:
     * $my_contents=getRenderedFileContents( $path='/path/to/file',$markdown=false,$embed_tags=false,$content_tags=false) 
     * Everything rendered: 
     * $my_contents=getRenderedFileContents( $path='/path/to/file',$markdown=true,$embed_tags=true,$content_tags=true) 
     * 
     * @param $path string The path to the file to be rendered
     * @param $markdown bool Whether you want to have the file parsed for markdown
     * @param $embed_tags bool True to render EMBED_TAGS , false to not render
     * @param $content_tags bool True to render CONTENT_TAGs , false to not render
     * @return string File contents
     */
    public function getRenderedFileContents( $path, $markdown = true, $embed_tags = true, $content_tags = true ) {


        /*
         * Get the Template
         */


        $template = $this->getTemplate( $template_path );



        $tags = $this->CONTENT_TAGS;
        if ( !is_array( $tags ) ) {
            $tags = array();
}
        /*
         * Merge in the Embed Tags But Only If we are parsing the template (not the content)
         */
        if ( !$this->is_content ){

            if ( is_array( $this->EMBED_TAGS ) ){
                $tags = array_merge( $tags, $this->EMBED_TAGS );
            }
        }

        $html = $this->crunchTpl( $tags, $template );

        if ( $echo )
        { echo $html; } else
            {
            return $html;
            }

    }

    /**
     * Render Template
     * @deprecated
     * Renders the template using the tags configured on config()
     *
     * @param $template_path The path to the template file
     * @param $echo boolean True to output to the browser (default) , false to return
     * @return void
     */
    public function renderTemplate( $template_path, $echo = true ) {


        /*
         * Get the Template
         */


        $template = $this->getTemplate( $template_path );



        $tags = $this->CONTENT_TAGS;
        if ( !is_array( $tags ) ) {
            $tags = array();
}
        /*
         * Merge in the Embed Tags But Only If we are parsing the template (not the content)
         */
        if ( !$this->is_content ){

            if ( is_array( $this->EMBED_TAGS ) ){
                $tags = array_merge( $tags, $this->EMBED_TAGS );
            }
        }

        $html = $this->crunchTpl( $tags, $template );

        if ( $echo )
        { echo $html; } else
            {
            return $html;
            }

    }

    /**
     * Crunch Template
     *
     * Replaces the key's tokens within the $template with the array's value for that key
     * A key token is simple the key with a bracket around it.
     * @example
     * $tags=array('name'=>'Joe','role'=>'admin');
     * $template='{NAME} is a great {ROLE}';
     * $html=crunchTpl($tags,$template);
     * $html is 'Joe is a great admin';
     *
     *
     *
     * @param array $tags An associative array containing tokens as indexes and replacements as its values.
     * @param string $template A string containing tokens to be replaced
     * @return void
     */
    public function crunchTpl( $tags, $template ) {

        /*
         * Check that $tags is array
         * and set to empty array so as not to throw any errors
         */
        if ( !is_array( $tags ) ) {

            $tags = array();
}


//        if (stripos($template, 'action') !== false) {
//            $this->debug()->logVar('$template = ', $template, true);
//            //$this->debug()->stop(true);
//        }

        /*
         * add a bracket around each key
         */
        foreach ( $tags as $key => $value ) {



            $value = $this->applyTagFilters( $key, $value );
            if ( is_array( $value ) || is_object( $value ) ) {
                $value = '<pre>' . print_r( $value, true ) . '</pre>';
            }

            $tags[ '{' . $key . '}' ] = $value;
            unset( $tags[ $key ] );
        }


        $html = str_ireplace( array_keys( $tags ), array_values( $tags ), $template );
        return $html;
    }

    /**
     * Set Config
     *
     * @param string $property_name
     * @param string $config_value
     *
     * @return object $this
     */
    protected function setConfig( $property_name, $config_value ) {

        $this->_config_properties[ $property_name ] = $config_value;

        return $this->_config_properties;
    }

    /**
     * Join Paths
     *
     * Joins the paths of multiple file paths together
     * @deprecated Use file_joinPaths instead
     * @see file_joinPaths 
     * @param $path string file path 
     * @return void
     */
    function joinPaths() {
        $paths = array();

        foreach ( func_get_args() as $arg ) {
            if ( $arg !== '' ) { $paths[] = $arg; }
    }

        return preg_replace( '#/+#', '/', join( '/', $paths ) );
}

    /**
     * Join Paths (File Library)
     *
     * Joins the paths of multiple file paths together
     * @param $path string file path 
     * @return void
     */
    function file_joinPaths() {
        $paths = array();

        foreach ( func_get_args() as $arg ) {
            if ( $arg !== '' ) { $paths[] = addslashes( $arg ); }
    }


        $paths = array_map( array( $this, 'file_convertToForwardSlashes' ), $paths );



        $result = preg_replace( '#/+#', '/', join( '/', $paths ) );


        return $result;
}

    /**
     * Read Configuration File
     *
     * Reads Configuration File and assigns settings to properties
     *
     * @param mixed A file path or an array of file paths
     * @return void
     */
    public function readConfigFile( $_config_file_path ) {




        /*
         * Resolve real file
         */

        $config_file_path = $this->file_getRealPath( $_config_file_path );



        /*
         * If file doesn't exist, return
         * 
         */
        if ( $config_file_path === false ) {
            return false;

} else {
            /*
             * If Json File exists,
             * Read it into a string
             */
            $json_config_string = file_get_contents( $config_file_path );
}




        /*
         * Make sure that $this->CONFIG is an array or merges will fail
         */
        if ( !isset( $this->CONFIG ) || !is_array( $this->CONFIG ) ){
            $this->CONFIG = array();

}

        /*
         * Decode Json into an array
         */
        $array_config = json_decode( $json_config_string, true );


        /*
         * Check if this is the site config file, and if so, check
         * if it's content_root has an alternate config file
         */
        if ( isset( $array_config[ 'SITE' ][ 'CONFIG' ] ) ){

            /*
             * check for a CONTENT setting. If true, look for an alternate setting file
             */


            if ( isset( $array_config[ 'SITE' ][ 'CONFIG' ][ 'CONTENT_ROOT' ] ) //check if there is a CONTENT_ROOT setting
                    && ($this->file_isSameDirectory( dirname( $config_file_path ), $this->getRootDirectory() ) ) ){ // and CONTENT_ROOT not set yet. This protects us from circular loop if 'CONTENT_ROOT' is in the config.json of the CONTENT_ROOT.
                //    $this->setConfig( 'CONTENT_ROOT', $array_config[ SITE ][ 'CONFIG' ][ 'CONTENT_ROOT' ] );

                /*
                 * Set Content Root
                 * This is the only place it will be set 
                 */
                $this->CONTENT_ROOT = $array_config[ 'SITE' ][ 'CONFIG' ][ 'CONTENT_ROOT' ];
                $alternate_config_file = $this->file_joinPaths( $array_config[ 'SITE' ][ 'CONFIG' ][ 'CONTENT_ROOT' ], "/config.json" );


                if ( $this->readConfigFile( $alternate_config_file ) === true ){

                    return true;
                     }

                }


    }





        /*
         * Use the first key to tell us how to merge $array_config into 
         * the CONFIG property
         */
        reset( $array_config );
        $first_key = key( $array_config );
        reset( $array_config );

        switch ( $first_key ){

            case 'SITE':
                $array_config = $this->_addMissingSiteConfig( $array_config );
                $this->CONFIG[ 'SITE' ] = $array_config[ 'SITE' ];
                $this->CONFIG[ 'SITE' ][ 'META' ][ 'CONFIG_FILE' ] = $config_file_path;


                break;
            case 'PLUGIN':
                $plugin_id = basename( dirname( $config_file_path ) );
                $this->CONFIG[ 'PLUGIN' ][ $plugin_id ] = $array_config[ 'PLUGIN' ];
                $this->CONFIG[ 'PLUGIN' ][ $plugin_id ][ 'META' ][ 'CONFIG_FILE' ] = $config_file_path;
                break;
            case 'TEMPLATE':
                $this->CONFIG[ 'TEMPLATE' ] = $array_config[ 'TEMPLATE' ];
                $this->CONFIG[ 'TEMPLATE' ][ 'META' ][ 'CONFIG_FILE' ] = $config_file_path;
                break;
            case 'SKIN':


                $skin_id = dirname( $_config_file_path );
                $this->CONFIG[ 'SKIN' ][ $skin_id ] = $array_config[ 'SKIN' ];
                $this->CONFIG[ 'SKIN' ][ $skin_id ][ 'META' ][ 'CONFIG_FILE' ] = $config_file_path;
                break;



}


        return true; //remove for debugging only


        /*
         * Add Content Tags
         */
        if ( isset( $array_config[ 'CONTENT_TAGS' ] ) ){
            foreach ( $array_config[ 'CONTENT_TAGS' ] as $tag_name => $tag_value ) {

                $this->addContentTag( $tag_name, $tag_value );

}
    }
        /*
         * Add Embed Tags
         */
        if ( isset( $array_config[ 'EMBED_TAGS' ] ) ){
            foreach ( $array_config[ 'EMBED_TAGS' ] as $tag_name => $tag_value ) {

                $this->addEmbedTag( $tag_name, $tag_value );

}
    }
        if ( false ){
            /*
             * Add Plugins
             */

            if ( isset( $array_config[ 'CONFIG' ][ 'PLUGINS' ] ) ){
                /*
                 * Read their plugin file
                 */
                foreach ( $array_config[ 'CONFIG' ][ 'PLUGINS' ] as $key => $plugin_name ) {

                    $this->readConfigFile( $this->file_joinPaths( 'plugins', $plugin_name, "/config.json" ) );

                    $this->_addPluginCSSandJs( $array_config[ 'CONFIG' ][ 'PLUGINS' ] );
                    /*
                     * Include their plugin file
                     */
                    $plugin_file = $this->file_joinPaths( 'plugins', $plugin_name, '/plugin.php' );

                    @include_once( $plugin_file);




}
    }
    }

        return true;
    }

    /**
     * Read Configuration File
     *
     * Reads Configuration File and Sets Configuration
     *
     * @param none
     * @return void
     */
    public function readConfigFileOLD( $config_file_path ) {
        /*
         *  Add Template Configuration 
         */


        /*
         * Add Skin Configuration
         */
        if ( file_exists( $config_file_path ) ) {
            $json_config = file_get_contents( $config_file_path );
} else {
            return false;

}

        $array_config = json_decode( $json_config, true );

        /*
         * Add Config
         */
        if ( isset( $array_config[ 'CONFIG' ] ) ){

            /*
             * check for a CONTENT setting. If true, look
             */


            if ( isset( $array_config[ 'CONFIG' ][ 'CONTENT_ROOT' ] ) && is_null( $this->CONTENT_ROOT ) ){

                $this->setConfig( 'CONTENT_ROOT', $array_config[ 'CONFIG' ][ 'CONTENT_ROOT' ] );

                $alternate_config_file = $this->file_joinPaths( $array_config[ 'CONFIG' ][ 'CONTENT_ROOT' ], "/config.json" );

                if ( $this->readConfigFile( $alternate_config_file ) === true ){

                    return true;
                     }

                }


            foreach ( $array_config[ 'CONFIG' ] as $config_name => $config_value ) {



                $this->setConfig( $config_name, $config_value );

}
    }


        /*
         * Add Content Tags
         */
        if ( isset( $array_config[ 'CONTENT_TAGS' ] ) ){
            foreach ( $array_config[ 'CONTENT_TAGS' ] as $tag_name => $tag_value ) {

                $this->addContentTag( $tag_name, $tag_value );

}
    }
        /*
         * Add Embed Tags
         */
        if ( isset( $array_config[ 'EMBED_TAGS' ] ) ){
            foreach ( $array_config[ 'EMBED_TAGS' ] as $tag_name => $tag_value ) {

                $this->addEmbedTag( $tag_name, $tag_value );

}
    }
        if ( false ){
            /*
             * Add Plugins
             */

            if ( isset( $array_config[ 'CONFIG' ][ 'PLUGINS' ] ) ){
                /*
                 * Read their plugin file
                 */
                foreach ( $array_config[ 'CONFIG' ][ 'PLUGINS' ] as $key => $plugin_name ) {

                    $this->readConfigFile( $this->file_joinPaths( 'plugins', $plugin_name, "/config.json" ) );

                    $this->_addPluginCSSandJs( $array_config[ 'CONFIG' ][ 'PLUGINS' ] );
                    /*
                     * Include their plugin file
                     */
                    $plugin_file = $this->file_joinPaths( 'plugins', $plugin_name, '/plugin.php' );

                    @include_once( $plugin_file);




}
    }
    }

        return true;
    }

    /**
     * Add Plugin CSS and JS
     *
     * Adds the Plugin CSS and JS to the PLUGIN_CSS and PLUGIN_JS Content Tags
     *
     * @param $plugins mixed The plugins relative path to its root directory. Either a string or an array
     * @return void
     */
    public function _addPluginCSSandJsOLD( $plugins ) {

        $css_string;
        $js_string = '';




        if ( !is_array( $plugins ) ){

            $plugins = array( $plugins );
        }



        foreach ( $plugins as $plugin_key => $root_directory ) {
            $root_directory = strtolower( $root_directory );
            $config_file = $this->file_joinPaths( 'plugins/', $root_directory, "/config.json" );

            if ( file_exists( $config_file ) ) {
                $json_config = file_get_contents( $config_file );


                $array_config = json_decode( $json_config, true );
                if ( !is_array( $array_config[ 'PLUGIN_CSS' ] ) ){

                    $array_config[ 'PLUGIN_CSS' ] = array( $array_config[ 'PLUGIN_CSS' ] );
        }

                foreach ( $array_config[ 'PLUGIN_CSS' ] as $css_key => $link ) {

                    $link = $this->file_joinPaths( "/plugins/", $root_directory, $link );
                    $css_string .="\n<link href=\"$link\" rel=\"stylesheet\">";


}

                if ( !is_array( $array_config[ 'PLUGIN_JS' ] ) ){

                    $array_config[ 'PLUGIN_JS' ] = array( $array_config[ 'PLUGIN_JS' ] );
        }
                foreach ( $array_config[ 'PLUGIN_JS' ] as $js_key => $link ) {

                    $link = $this->file_joinPaths( "/plugins/", $root_directory, $link );
                    $js_string .="\n<script src=\"$link\"></script>";

}

}
}

        $this->addContentTag( "CSS", $css_string );
        $this->addContentTag( "S", $js_string );

    }

    /**
     * Read Skin Configuration Files
     *
     * Reads the Skin Configuration Files Configured in the root config.json and creates the associated content tags
     *
     * @param $skin mixed The skin's relative path to its root directory. Either a string or an array of strings
     * @return void
     */
    public function readSkinConfigFilesOLD( $skin_root_directories ) {

        $css_string;
        $js_string = '';




        if ( !is_array( $skin_root_directories ) ){

            $skin_root_directories = array( $skin_root_directories );
        }


        foreach ( $skin_root_directories as $directory_key => $_skin_root_directory ) {
            $skin_root_directory = strtolower( $_skin_root_directory );
            $skin_config_file = "skins/" . strtolower( $skin_root_directory ) . "/config.json";

            if ( file_exists( $skin_config_file ) ) {
                $json_skin_config = file_get_contents( $skin_config_file );


                $array_skin_config = json_decode( $json_skin_config, true );
                if ( !is_array( $array_skin_config[ 'CSS' ] ) ){

                    $array_skin_config[ 'CSS' ] = array( $array_skin_config[ 'CSS' ] );
        }

                foreach ( $array_skin_config[ 'CSS' ] as $css_key => $link ) {

                    $link = $this->file_joinPaths( "/skins/", $skin_root_directory, $link );
                    $css_string .="\n<link href=\"$link\" rel=\"stylesheet\">";


}

                if ( !is_array( $array_skin_config[ 'JS' ] ) ){

                    $array_skin_config[ 'JS' ] = array( $array_skin_config[ 'JS' ] );
        }
                foreach ( $array_skin_config[ 'JS' ] as $js_key => $link ) {

                    $link = $this->file_joinPaths( "/skins/", $skin_root_directory, $link );
                    $js_string .="\n<script src=\"$link\"></script>";

}

}
}

        $this->addContentTag( "SKIN_CSS", $css_string );
        $this->addContentTag( "SKIN_JS", $js_string );

       }

    /**
     * Get File From Url
     *
     * Returns a file based on order or precedence
     *
     * @param $url string The requested url
     * @return mixed The path to the file or false if it could not be found
     */
    public function getFileFromUrl( $url ) {



        /*
         * Capture the url's parts
         */

        $url_info = (parse_url( $url ));
        $file_info = pathinfo( $url_info[ 'path' ] );



        /*
         * Add Trailing Slash
         * If not a file.
         * This allows /about and /about/ to both return /about/index.md, /about.md ,etc.
         */
        if ( !isset( $file_info[ 'extension' ] ) ) {
            $url_info[ 'path' ] = $this->addTrailingSlash( $url_info[ 'path' ] );

            $url = $url_info[ 'path' ];
        }


        /*
         * Add a Content Tag for Each Query Variable so We can add their values as content tags if we want
         */
        if ( isset( $url_info[ 'query' ] ) ){
            parse_str( $url_info[ 'query' ], $query_vars );

            foreach ( $query_vars as $key => $value ) {
                $this->addContentTag( $key, $value );
}
}


        /* Sample result for url=http://bootdown.com/myphp.php?test=1
         * 
         * $url_info
          (
          [path] => /myphp.php
          [query] => test=1
          )
          file_info
          (
          [dirname] => \
          [basename] => myphp.php
          [extension] => php
          [filename] => myphp
          )




         */
        /*
         * 
         * 
         */
        /*
         * Determine True File Path and Extension
         * 
         * 
         * 
         */

        /*
         * If the url includes a file extension, use that file
         */
        if ( isset( $file_info[ 'extension' ] ) ){
            $file[ 'extension' ] = $file_info[ 'extension' ];
            $file[ 'path' ] = $this->file_getRealPath( ($this->file_joinPaths( $this->CONTENT_ROOT, $url_info[ 'path' ] ) ) );
} else //otherwise, if it ends in a slash, assume you want to serve index.md
        {

            /*
             *  Get File From URL
             * If URL Ends in Slash
             *  Look for a file in order of precedence
              Example: http://example.com/about/
              (
              [0] => C:/wamp/www/example.com/about.md
              [1] => C:/wamp/www/example.com/content/about.md
              [2] => C:/wamp/www/example.com/content/about/index.php
              [3] => C:/wamp/www/example.com/content/about/index.html
              [4] => C:/wamp/www/example.com/content/about/index.html
              [5] => C:/wamp/www/example.com/content/about/index.md
              )
             * This 
             * This array will be a number of possible files that will
             */

   
            /*
             * Add /about.md
             * 
              [0] => C:/wamp/www/example.com/about.md

             */
            $default_files[] = ( $this->file_getRealPath( $this->file_joinPaths( $file_info[ 'dirname' ], $file_info[ 'basename' ] . $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'MARKDOWN_EXTENSION' ] ) ) );

            /*
             * Add /content/about.md
              [1] => C:/wamp/www/example.com/content/about.md
             */

            $default_files[] = ( $this->file_getRealPath( $this->file_joinPaths( $this->CONTENT_ROOT, $file_info[ 'dirname' ], $file_info[ 'basename' ] . $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'MARKDOWN_EXTENSION' ] ) ) );


            /*
             * Add the configued INDEX_FILES to the search list
             * 
              [2] => C:/wamp/www/example.com/content/about/index.php
              [3] => C:/wamp/www/example.com/content/about/index.html
              [4] => C:/wamp/www/example.com/content/about/index.html
              [5] => C:/wamp/www/example.com/content/about/index.md
             */


            $default_basenames = $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'INDEX_FILES' ];

            /*
             * Add the configured default files to the list of files we're looking for.
             */
            foreach ( $default_basenames as $key => $default_file_name ) {
                $default_files[] = ( $this->file_getRealPath( $this->file_joinPaths( $this->CONTENT_ROOT, $url_info[ 'path' ], $default_file_name ) ));
}


            /*
             * Filter array for empty elements
             * Elements would be null if file_getRealPath found they didn't exist
             */

            $default_files = array_filter( $default_files );



            if ( empty( $default_files ) ){ //if array is empty, set it to false so we get a 404
                $file[ 'path' ] = false;
            } else {
                $file[ 'path' ] = array_shift( $default_files ); //get the file with the highest  precedence


                $file_info = pathinfo( $file[ 'path' ] );


                //    $file[ 'path' ] = $this->file_getRealPath( ($this->file_joinPaths( $this->CONTENT_ROOT, $this->addTrailingSlash( $url_info[ 'path' ] ) ) . "index.md" ) );
                $file[ 'extension' ] = $file_info[ 'extension' ];
            }





        }
        if ( $file[ 'path' ] === false ) { $file = false; }

        /*
         * Update Page Info
         */

        $this->_page_info[ 'url' ] = $url;
        $this->_page_info[ 'file_path' ] = isset( $file[ 'path' ] ) ? $file[ 'path' ] : null;
        $this->_page_info[ 'file_extension' ] = isset( $file[ 'extension' ] ) ? $file[ 'extension' ] : null;
        $this->_page_info[ 'query' ] = isset( $url_info[ 'query' ] ) ? $url_info[ 'query' ] : null;
        $this->_page_info[ 'query_vars' ] = isset( $query_vars ) ? $query_vars : array();

        /*
         * Earliest that $url can be determined correctly
         */
        $this->doActionHooks( 'dc_page_info' );


        return $file;
    }

    private $_tag_filters = null;

    /**
     * Add Tag Filter
     *
     * Adds a callback function to the tag filters array
     * Usage:
     * $this->addTagFilter('CONTENT',array($this,'addFooter');
     * @param $tagname string The tag's name 
     * @param $callback callable The callback function or method
     * @return void
     */
    public function addTagFilter( $tagname, $callback ) {

        $this->_tag_filters[ $tagname ][] = $callback;


    }

    /**
     * Apply Tag Filters
     *
     * Returns the output of the filter callbacks on $content
     *
     * @param $content string The content to be filtered
     * @return string The filtered content
     */
    public function applyTagFilters( $tagname, $content ) {




        if ( isset( $this->_tag_filters[ $tagname ] ) ) {


            foreach ( $this->_tag_filters[ $tagname ] as $key => $callback ) {

                if ( is_callable( $callback ) ) {

                    $content = call_user_func( $callback, $tagname, $content );


}

}



}

        return $content;


    }

    private $_method_overrides = null;

    /**
     * Add Method Override
     *
     * Enables Plugins to Override a Method
     * Returns true on success, the 'id' of the conflicting plugin on failure. If it fails its because another plugin already overrode the method.
     * Usage:

      $override = $this->downcast()->addMethodOverride( 'parseMarkdown', array( $this, 'parseMarkdown' ),__CLASS__);
      if ( $override !== true )
      {
      echo '<br>Plugin error: cannot override method, plugin ' . $override . ' conflicts';

      }
     * @param $method_name string The method name
     * @param $callback callable The callback function or method
     * @param $id string Any unique string that identifies the caller, usually the Plugin name. This allows other plugins to determine when there is a conflict.
     * @return void
     */
    public function addMethodOverride( $method_name, $callback, $id ) {
        if ( !isset( $this->_method_overrides[ $method_name ] ) ) {
            $this->_method_overrides[ $method_name ] = array();
            $this->_method_overrides[ $method_name ][ 'callback' ] = $callback;
            $this->_method_overrides[ $method_name ][ 'id' ] = $id;
            return true;
} else
    { return $this->_method_overrides[ $method_name ][ 'id' ];

}

    }

    /**
     * Apply Method Override
     *
     * Returns the output of the filter callbacks on $content
     * Usage:
     * $result_override=applyMethodOverride( __FUNCTION__, $args )
     * if ($result_override!==false){return $result_override['override_return_result'];}
     * @param $method_name string The method in the Downcast class to be overriden
     * @param $args array An array of arguments to be passed to the callback.
     * @return mixed False means there was no override. If result is an array, it was overridden and the result is in $result[ 'override_return_result' ] 
     */
    public function applyMethodOverride( $method_name, $args = null ) {

        $result[ 'override_return_result' ] = null;
        $result[ 'override_id' ] = null;
        if ( isset( $this->_method_overrides[ $method_name ] ) ) {
            $override = $this->_method_overrides[ $method_name ];

            $callback = $override[ 'callback' ];
            if ( is_callable( $callback ) ) {


                $result[ 'override_return_result' ] = $override_result = call_user_func( $callback, $args );
                $result[ 'override_id' ] = $override[ 'id' ];
}





} else {
            $result = false;

}

        return $result;


   }

    private $_plugins = null; //stores an array of plugin objects

    /**
     * Add Plugin
     *
     * Creates the Plugin and adds it to the $this->_plugins aray.
     *
     * @param none
     * @return void
     */

    private function _addPlugin( $plugin_name ) {


        if ( !isset( $this->_plugins[ $plugin_name ] ) ) {
            include_once("plugins/$plugin_name/$plugin_name.php");
            $this->_plugins[] = new $plugin_name( $this );
}


    }

    /**
     * Get Plugin
     *
     * Returns a plugin object
     *
     * @param none
     * @return void
     */
    public function getPlugin( $plugin_name ) {

        if ( isset( $this->_plugins[ $plugin_name ] ) ) {
            return $this->_plugins[ $plugin_name ];
} else {

            return null;
}


}

    /**
     * Parse Markdown
     *
     * Parses Markdown (Can be overridden by plugins)
     *
     * @param none
     * @return string The parsed text in Markdown
     */
    public function parseMarkdown( $text ) {

        $result_override = $this->applyMethodOverride( __FUNCTION__, array( 'text' => $text ) );
        if ( $result_override !== false ){


            return $result_override[ 'override_return_result' ];


        }
        include_once ("lib/bootdown/bootdown.php"); //add the bootdown library here  so we don't run into problems with a globally declared Markdown function with plugins.
        return Markdown( $text );

    }

    private $_action_hooks;

    /**
     * Do Action Hooks
     *
     * Executes Any Action Hooks
     *
     * @param $hook_name string The name of the hook
     * @return void
     */
    public function doActionHooks( $hook_name, $args = array() ) {


        $this->debugLog( 'doActionHooks(' . $hook_name . ')', '', __FUNCTION__, false );

        if ( isset( $this->_action_hooks[ $hook_name ] ) ) {
            foreach ( $this->_action_hooks[ $hook_name ] as $key => $callback ) {

                if ( is_callable( $callback ) ) {


                    call_user_func_array( $callback, $args );


}
            }
        }
}

    /**
     * Add Action Hook
     *
     * Adds a callback function to the action_hooks array
     * Usage:
     * $this->addActionHook('before_template',array($this,'addHeader');
     * @param $hook_name string The hook's name . Must match a predefined hook
     * @param $callback callable The callback function or method
     * @return void
     */
    public function addActionHook( $hook_name, $callback ) {
        $this->debugLog( 'addActionHook(' . $hook_name . ')', '', __FUNCTION__, false );
        $this->_action_hooks[ $hook_name ][] = $callback;


    }

    private $_root_directory = null;

    /**
     * Set Root Directory
     *
     * Sets the Root Directory Path
     *
     * @param none
     * @return void
     */
    public function setRootDirectory( $path ) {

        $this->_root_directory = $path;

        }

    /**
     * Get Root Directory Path (**Not** Content Root)
     *
     * Returns the base directory path of the DownCast root directory (**NOT content root!)
     * @param none
     * @return string The path to the root directory
     */
    public function getRootDirectory() {
        return $this->file_convertToForwardSlashes( $this->_root_directory );

    }

    /**
     * Add Missing Site Config
     *
     * Provide Defaults for values that if missing, e.g. in the event the main site's config file is deleted, would break the site.
     * Do not provide defaults for tags or info, since it may be intentional that
     * they were left blank.
     * Usage:
     * 
     * 
     * 
     * @param $array_config
     * @return array The site's configuration array, with missing configuration added back in.
     */
    private function _addMissingSiteConfig( $array_config ) {




        $defaults = '
           {"SITE":
            {
                "INFO":
                        {
                        },
                "CONFIG":
                        {    
                        
                            "CONTENT_ROOT": "content",
                            "SKIN": [
                                "/bootstrap/2.3.2/superhero",
                                "/highlightjs/8.3"

                            ],
                            "PLUGINS": [
                            ],
                            "TEMPLATE": "fluid",
                            "MARKDOWN_EXTENSION": ".md",
                            "INDEX_FILES": [
                                "index.php",
                                "index.html",
                                "index.html",
                                "index.md"
                            ],
                            "CSS_INLINE_ENABLED": false,
                            "JS_INLINE_ENABLED": false
                            
                        },
                "EMBED_TAGS": {
                },
                "CONTENT_TAGS": {
                }
            }
}  

';

        $defaults_config = json_decode( $defaults, true );

        foreach ( $defaults_config[ 'SITE' ][ 'CONFIG' ] as $key => $value ) {
            if ( !isset( $array_config[ 'SITE' ][ 'CONFIG' ][ $key ] ) ){
                $array_config[ 'SITE' ][ 'CONFIG' ][ $key ] = $defaults_config[ 'SITE' ][ 'CONFIG' ][ $key ];

            }
}
        return $array_config;
    }

    /**
     * Load Site Configuration
     *
     * Reads Configuration Files into the $this->SITE property
     *
     * @param none
     * @return void
     */
    private function _loadSiteConfigFile() {

        $this->doActionHooks( 'dc_site_config' );
        if ( $this->readConfigFile( $this->SITE_CONFIG_FILE_PATH ) === false ){

            $error_title = "Unable to start. Missing Configuration file " . $this->SITE_CONFIG_FILE_PATH . "'";
            $error_details = 'If you\'ve relocated it, update its location by editing the config() method in /lib/downcast/Downcast.php and updating $this->SITE_CONFIG_FILE_PATH' . ' Or you may replace the file with a fresh download from GetDownCast.com';

            $this->debugLogError( $error_title, $error_details );

            exit(); //exit script

}

    }

    /**
     * Load Template Configuration
     *
     * Reads Configuration Files into the $this->SITE property
     *
     * @param none
     * @return void
     */
    private function _loadTemplateConfigFile() {
        /*
         *  Add Template Configuration 
         */


        $this->doActionHooks( 'dc_template_config' );
        $this->readConfigFile( $this->file_joinPaths( "templates/", strtolower( $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'TEMPLATE' ] ), "/config.json" ) );

    }

    /**
     * Load Skin Configuration
     *
     * Reads Configuration Files into the $this->SITE property
     *
     * @param none
     * @return void
     */
    private function _loadSkinConfigFile() {

        /*
         *  Add Skin Configuration
         * Check first if its an array or string
         */

        /*
         * Override setting skin setting here
         */
        $this->doActionHooks( 'dc_skin_config' );

        if ( !is_array( $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ])) {
           $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ]=array( $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ]);  
}
      


            $array_skin_directories = $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'SKIN' ];
            
            
            
            foreach ( $array_skin_directories as $skin_directory ) {



                $skin_path = $this->file_joinPaths( "skins", strtolower( $skin_directory ), "/config.json" );



                $this->readConfigFile( $skin_path );
}








}

    /**
     * Load Plugins ( internal ) 
     *
     * Instantiates Each Plugin and Assigns to a Tracking Array in $this
     *
     * @param none
     * @return void
     */
    private function _loadPlugins() {

        $plugins = $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'PLUGINS' ];
        $load_failed = false;
        foreach ( $plugins as $key => $plugin_unique_id ) {
            if ( !isset( $this->_plugins[ $plugin_unique_id ] ) ) {

                $plugin_file_path = $this->file_joinPaths( "plugins/", $plugin_unique_id, $plugin_unique_id . ".php" );
                $plugin_configuration_file = $this->file_joinPaths( "plugins/", $plugin_unique_id, "config.json" );
                if ( (!file_exists( $plugin_file_path )) || (!file_exists( $plugin_configuration_file )) ) {
                    $load_failed = true;
            }

                @include_once($plugin_file_path);

                if ( !class_exists( $plugin_unique_id ) ) {
                    $load_failed = true;


                    }

                if ( $load_failed ){


                    $error_title = "Plugin $plugin_unique_id failed to load";
                    $error_details = "The class, parent directory name, and/or plugin class file name do not match, or you are missing the plugin class file or configuration. Please see DownCast Documentation for more information";

                    $this->debugLogError( $error_title, $error_details );

                    $load_failed = false; //reset for next plugin load
                    } else {
                    $this->_plugins[ $plugin_unique_id ] = new $plugin_unique_id( $this ); //instantiate the plugin object and assign to $this->_plugins
                    }


}

}















    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function addSiteTags() {
        
    }

    /**
     * Initialize Plugins
     *
     * Calls the _plugin_init method of each loaded plugin
     *
     * @param none
     * @return void
     */
    private function _initPlugins() {
        /*
         * If no plugins,return
         */
        if ( !isset( $this->CONFIG[ 'PLUGIN' ] ) ) {
            return;
}
        $plugins = $this->CONFIG[ 'PLUGIN' ];
        foreach ( $plugins as $plugin_id => $plugin_config_array ) {
            $this->_plugins[ $plugin_id ]->_plugin_init();
}
    }

    /**
     * Add Tags
     *
     * Adds Site Embed and Content Tags
     *
     * @param none
     * @return void
     */
    private function _addTags() {

        /*
         * Site Tags
         */
        $site_embed_tags = $this->CONFIG[ 'SITE' ][ 'EMBED_TAGS' ];
        $site_content_tags = $this->CONFIG[ 'SITE' ][ 'CONTENT_TAGS' ];


        /*
         * Add SITE EMBED_TAGS
         */


        foreach ( $site_embed_tags as $tag_name => $tag_content ) {
            $this->addEmbedTag( $tag_name, $tag_content );
}


        /*
         * Add SITE CONTENT_TAGS
         */
        foreach ( $site_content_tags as $tag_name => $tag_content ) {
            $this->addContentTag( $tag_name, $tag_content );
}

        /*
         * Convert Site INFO Tags into Content Tags
         */

        $info_tags = $this->CONFIG[ 'SITE' ][ 'INFO' ];






        foreach ( $info_tags as $tag_name => $tag_content ) {
            $this->addContentTag( 'SITE_' . $tag_name, $tag_content );
}


        /*
         * Add Template Tags
         */
        $template_embed_tags = $this->CONFIG[ 'TEMPLATE' ][ 'EMBED_TAGS' ];
        $template_content_tags = $this->CONFIG[ 'TEMPLATE' ][ 'CONTENT_TAGS' ];
        /*
         * Add Template EMBED_TAGS
         */
        foreach ( $template_embed_tags as $tag_name => $tag_content ) {
            $this->addEmbedTag( $tag_name, $tag_content );
}
        /*
         * Add Template CONTENT_TAGS
         */
        foreach ( $template_content_tags as $tag_name => $tag_content ) {
            $this->addContentTag( $tag_name, $tag_content );
}


}

    /**
     * Add Skin CSS and Javascript
     *
     * Returns Skin's CSS and Javascript as HTML LINK tags
     *
     * @param none
     * @return void
     */
    private function _getSkinCssAndJs() {
        /*
         * Initialize
         */

        $js_links = '';
        $js_inline = '';
        $css_links = '';
        $css_inline = '';
        $result[ 'js_inline' ] = '';
        $result[ 'js_links' ] = '';
        $result[ 'css_inline' ] = '';
        $result[ 'css_links' ] = '';
        /*
         * Find Skin Array
         */
        if ( isset( $this->CONFIG[ 'SKIN' ] ) ) {
            $array_skin = $this->CONFIG[ 'SKIN' ];
} else {
            /*
             * return empty strings if no SKIN loaded
             */
            return $result;
}



        /*
         * Force it to be an array to avoid errors when passed a string
         */
        if ( !is_array( $array_skin ) ) {
            $array_skin = array( $array_skin );
}
        /*
         * Iterate through each skin and concatenate their css paths
         */
        foreach ( $array_skin as $skin_relative_path => $skin ) {


            $resource_array = (isset( $skin[ 'CONFIG' ][ 'CSS' ] )) ? $skin[ 'CONFIG' ][ 'CSS' ] : array();
            $css_links .=$this->_implodeResourceArray(
                    $skin_relative_path, //The relative path from Downcast root to the resource directory
                    $resource_array, //The array containing the names of the resource files
                    true, //$css True if the resource is css, false if script
                    false //$inline True for inline, false to return as resource links                      
            );

            $resource_array = (isset( $skin[ 'CONFIG' ][ 'CSS_INLINE' ] )) ? $skin[ 'CONFIG' ][ 'CSS_INLINE' ] : array();


            $css_inline .=$this->_implodeResourceArray(
                    $skin_relative_path, //The relative path from Downcast root to the resource directory
                    $resource_array, //The array containing the names of the resource files
                    true, //$css True if the resource is css, false if script
                    $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'CSS_INLINE_ENABLED' ] //$inline True for inline, false to return as resource links 
            );

            $resource_array = (isset( $skin[ 'CONFIG' ][ 'JS' ] )) ? $skin[ 'CONFIG' ][ 'JS' ] : array();

            $js_links .=$this->_implodeResourceArray(
                    $skin_relative_path, //The relative path from Downcast root to the resource directory
                    $resource_array, //The array containing the names of the resource files
                    false, //$css True if the resource is css, false if script
                    false //$inline True for inline, false to return as resource links 
            );


            $resource_array = (isset( $skin[ 'CONFIG' ][ 'JS_INLINE' ] )) ? $skin[ 'CONFIG' ][ 'JS_INLINE' ] : array();

            $js_inline .=$this->_implodeResourceArray(
                    $skin_relative_path, //The relative path from Downcast root to the resource directory
                    $resource_array, //The array containing the names of the resource files
                    false, //$css True if the resource is css, false if script
                    $this->CONFIG[ 'SITE' ][ 'CONFIG' ][ 'JS_INLINE_ENABLED' ]  //$inline True for inline, false to return as resource links 
            );




}//end SKINs Loop




        $result[ 'css_links' ] = $css_links;
        $result[ 'css_inline' ] = $css_inline;

        $result[ 'js_links' ] = $js_links;
        $result[ 'js_inline' ] = $js_inline;


        return $result;
    }

    /**
     * Get Plugin CSS and Javascript
     *
     * Returns Plugins' CSS and Javascript as HTML LINK tags
     *
     * @param none
     * @return void
     */
    private function _getPluginCssAndJs() {
        /*
         * Initialize
         */
        $css = '';
        $js = '';
        $result[ 'css' ] = $css;
        $result[ 'js' ] = $js;

        /*
         * Find Plugin Array
         */
        if ( isset( $this->CONFIG[ 'PLUGIN' ] ) ) {
            $array_plugins = $this->CONFIG[ 'PLUGIN' ];
} else {
            /*
             * return empty strings if no SKIN loaded
             */
            return $result;
}



        /*
         * Iterate through each Plugin and concatenate their css paths
         */
        foreach ( $array_plugins as $plugin_id => $plugin ) {

            $plugin_relative_path = "plugins/$plugin_id";
            if ( isset( $plugin[ 'CONFIG' ][ 'CSS' ] ) ){
                $plugin_css_array = $plugin[ 'CONFIG' ][ 'CSS' ];
                if ( !is_array( $plugin_css_array ) ) {
                    $plugin_css_array = array( $plugin_css_array );
}


                foreach ( $plugin_css_array as $css_key => $css_path ) {

                    $link = $this->file_convertToForwardSlashes( $this->file_joinPaths( $plugin_relative_path, $css_path ) );

                    $css .="\n<link href=\"$link\" rel=\"stylesheet\">";


}

               }


            /*
             * Javascript
             */
            if ( isset( $plugin[ 'CONFIG' ][ 'JS' ] ) ){
                $plugin_js_array = $plugin[ 'CONFIG' ][ 'JS' ];
                if ( !is_array( $plugin_js_array ) ) {
                    $plugin_js_array = array( $plugin_js_array );
}

                /*
                 * Iterate through JS Array
                 */

                foreach ( $plugin_js_array as $js_key => $js_path ) {

                    $link = $this->file_convertToForwardSlashes( $this->file_joinPaths( $plugin_relative_path, $js_path ) );
                    $js .="\n<script src=\"$link\"></script>";


}
        }

        }

        $result[ 'css' ] = $css;
        $result[ 'js' ] = $js;







        return $result;
       }

    /**
     * Add Css and Script Tags
     *
     * Adds Content Tags {CSS} and {JS} based on Skin and Plugin Configuration
     *
     * @param none
     * @return void
     */
    private function _addCssAndJsTags() {

        /*
         * Initialize
         */
        $css_links = '';
        $css_inline = '';
        $js_links = '';
        $js_inline = '';


        /*
         * Get the Skin's CSS and Scripts
         */

        $this->doActionHooks( 'dc_skin_resources' );
        $skin_links = $this->_getSkinCssAndJs();

        $css_links .=$skin_links[ 'css_links' ];
        $css_inline .=$skin_links[ 'css_inline' ];
        $js_links .=$skin_links[ 'js_links' ];
        $js_inline .=$skin_links[ 'js_inline' ];

        /*
         * Get the Plugin CSS and Scripts
         */
        if ( false ){
            $plugin_links = $this->_getPluginCssAndJs();

            $css_links .=$plugin_links[ 'css_links' ];
            $css_inline .=$plugin_links[ 'css_inline' ];
            $js_links .=$plugin_links[ 'js_links' ];
            $js_inline .=$plugin_links[ 'js_inline' ];
}




        $css_tag_content = $css_links . $css_inline;
        $js_tag_content = $js_links . ($js_inline); //encode is necessary to escape javascript
        $js_tag_content_encoded = $js_links . $js_inline; //encode is necessary to escape javascript
        //  echo ($js_tag_content_encoded);
        // $this->debugLog( 'stop = ', stop, true, true );
//        $this->debugLog( '$js_tag_content = ', $js_tag_content, true, true );

        $this->addContentTag( 'CSS', $css_tag_content );
        $this->addContentTag( 'JS', $js_tag_content );





    }

    /**
     * Log Error Message
     *
     * Logs Error Message to Log File or to screen
     * This should be reserved for user type errors, not for development debugging.
     *
     * @param none
     * @return void
     */
    public function debugLogError( $_error_title, $_error_details ) {
        if ( !$this->DEBUG ) {
            return;
}

        if ( $this->DEBUG_LOG_TO_FILE ) {
            $log_file_message = date( "F j, Y, g:i a" ) . ' ' . $_error_title . ' ' . $_error_details . "\n";
            error_log( $log_file_message, 3, $this->DEBUG_FILE );
}


        $html_error_title = '<strong>' . $this->STRING_DEBUG_LABEL . ' ' . $_error_title . '</strong>';
        $html_error_details = '<span>' . $_error_details . '</span>';
        $html_message = '<p>' . $html_error_title . ' ' . $html_error_details . '</p>';

        if ( $this->DEBUG_SHOW_ERRORS ) { //0 - log, 1, screen , 2 log and show
            echo $html_message;
}


    }

    /**
     * Debug Log
     *
     * Prints out debug text if its in the filter
     *
     * @param $text string Text to be displayed
     * @param $var mixed The variable to be displayed
     * @param $filter mixed A boolean or a unique string that if in allowed_filters permits the text to be displayed
     * @return void
     */
    public function debugLog( $text, $var, $filter = true, $stop = false ) {
        /*
         * filters
         * 'cache'
         * 
         */
        $debug_array = debug_backtrace();
        $line = $debug_array[ 0 ][ 'line' ];
        $file = $debug_array[ 0 ][ 'file' ];


        $line = is_null( $line ) ? "" : "($line)";

        if ( !$this->DEBUG ) {
            return;
}

        $allowed_filters = array(
            'doActionHooks',
            'addActionHook'
        );

        if ( is_bool( $filter ) ) {
            if ( !$filter ){ return; }
            $debug = $filter;

} else {
            $debug = in_array( $filter, $allowed_filters );
}
        if ( $debug ){
            if ( is_array( $var ) ){
                echo "<br><strong style=\"color:green\">$line Debug:</strong><pre>" . $text, htmlspecialchars( print_r( $var, true ) ), '</pre>';
} else {

                echo "<br><strong style=\"color:green\">$line Debug:</strong><pre>" . $text . ' ' . htmlspecialchars( $var );
}


    }

        if ( $stop ) {
            /*
             * Add Backtrace
             * Grab the nice ouput of debug_print_backtrace using
             * output buffering, 
             * then use regex to remove the first part since its the debug function call that we alread have
             */
            ob_start();
            debug_print_backtrace();
            $backtrace = htmlspecialchars( ob_get_clean() );



            $pattern = '/#0(?:(?!#1).)*/ims';
            $replacement = '';
            $backtrace = preg_replace( $pattern, $replacement, $backtrace );



            echo '<br><div style="color:brown;"><pre>########   BACKTRACE ########<br>', $backtrace, '</pre</div>';
            die( "<br>Exited for debug $line " . $file );


    }


}

    /**
     * is Same Directory ( File Library )
     *
     * Normalizes and Compares 2 directory paths.
     *
     * @param $_path1  - The path to the first directory
     * @param $_path2  - The path to the second directory
     * 
     * @return bool True if same, false if different
     */
    public function file_isSameDirectory( $_path1, $_path2 ) {

        $path1 = $this->addTrailingSlash( $this->file_convertToForwardSlashes( trim( $_path1 ) ) );
        $path2 = $this->addTrailingSlash( $this->file_convertToForwardSlashes( trim( $_path2 ) ) );

        return ($path1 === $path2);


    }

    /**
     * Convert to Forward Slashes  ( File Library )
     *
     * Useful for Relative URL Paths and to normalize paths.
     *
     * @param $path Path containing backslashes
     * @return void
     */
    public function file_convertToForwardSlashes( $path ) {

        $result = (str_replace( '\\', '/', $path ));

        return $result;
    }

    /**
     * Get Real Path ( File Library)
     *
     * 
     * Does exactly the same as realpath except  by default it does not resolve symbolic links and ../ type paths
     * This is for security reasons. If you want to override this, and use realpath, set safe=false;
     * 
     * If you need to get a Real Path without checking if file exists, use file_getAbsolutePath
     * 
     * 
     * @param $path The path to the file or directory
     * @param $safe bool True will not use realpath, false uses realpath. Its unsafe because it will allow an attacker to use ../ and have it resolved to a real file. 
     * @return void
     */
    public function file_getRealPath( $path, $safe = true ) {

        /*
         * Use realpath only if safe is false.
         */
        if ( $safe === true ){

            $abs_file_path = $this->file_joinPaths( $this->getRootDirectory(), $path );




            if ( file_exists( $abs_file_path ) ) {

                $result = $abs_file_path;

} else {
                $result = false;
}

   } else {

            $result = realpath( $path );

   }
        return $result;
    }

    /**
     * Get Absolute Path ( File Library)
     *
     * 
     * Simply adds the app's root directory to the path provided. 
     * It does not resolve ../ or symbolic links.
     * Does not check if file exists
     * If you need to check if file exists, use file_getRealPath
     * 
     * 
     * @param $path string The path to the file or directory
     * @return string The absolute path.
     */
    public function file_getAbsolutePath( $path ) {

        $result = $this->file_joinPaths( $this->getRootDirectory(), $path );

        return $result;
   }

    /**
     * Implode Resource Array
     *
     * Converts a resource array into a string containining inline css or js, or resource links
     *
     * 
     * Usage:
     * $inline_js=$this->_implodeResourceArray('/js/',$js_inline_array,false,false);
     * 
     * @param $resource_relative_path string The relative path from Downcast root to the resource directory containing the resource
     * @param $resource_array The array containing the names of the resource files
     * @param $css bool True if the resource is css, false if script
     * @param $inline bool True if you want the resources returned as inline, false to return as resource links 
     * @return string A concatenated string containing the resources either as inline <style></style> and <script></script> tags or linked resources
     */
    private function _implodeResourceArray( $resource_relative_path, $resource_array, $css = true, $inline = false ) {
        //initialize
        $string = '';

        /*
         * Force array to avoid errors when passed a string
         */
        if ( !is_array( $resource_array ) ) {
            $resource_array = array( $resource_array );
}

        /*
         * Iterate Array
         * 
         * e.g.:
         * 0=>"css/bootstrap.min.css"
         * 1=>"css/bootstrap-responsive.min.css"
         * 3=>"http://example.com/css/bootstrap-responsive.min.css"
         */

        foreach ( $resource_array as $key => $path ) {


            /*
             * Check for External URLs and Use Link Form regardleess of $inline setting
             */
            if ( preg_match( '/^http/', $path, $matches ) ){
                if ( $css ) {
                    $string .="\n<link href=\"$path\" rel=\"stylesheet\">";
} else {
                    $string.="\n<script type=\"text/javascript\" src=\"$path\"></script>";
}


} else
            /*
             * If Not an external url, check $inline setting and add Inline or Link form depending on setting
             */

    {
                $link = $this->addLeadingSlash( $this->file_joinPaths( $resource_relative_path, $path ) );
                if ( $inline ){
                    /*
                     * If Inline, read the file and add to the result string
                     */
                    if ( $css ) {
                        $string.= "\n<style>" . file_get_contents( $this->file_getRealPath( $link ) ) . '</style>';
  } else {
                        $string.= "\n<script type=\"text/javascript\" >" . file_get_contents( $this->file_getRealPath( $link ) ) . '</script>';
  }
} else {
                    /*
                     * 
                     * If Not Inline and Not External, 
                     * then just link to it
                     * 

                     */


                    if ( $css ) {
                        $string .="\n<link href=\"$link\" rel=\"stylesheet\">";
} else {
                        $string.="\n<script type=\"text/javascript\" src=\"$link\"></script>";

}




}

    }

}
        return $string;
    }

}

?>