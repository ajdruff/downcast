<?php

/**
 * MarkdownIO (Parent Class)
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

    private $_page_info = null;

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
        //$this->addPlugin( 'HelloWorld' );
        //$this->addPlugin( 'Parsedowner' );
        // $this->addPlugin( 'Parsedowner' );
        // $this->addPlugin( 'JustText' );
        //$this->addPlugin( 'Templater' );
        $this->addPlugin( 'Tagger' );
        /*
         * Add Site Configuration
         */

        $this->readConfigFile( "config.json" );


        /*
         *  Add Template Configuration 
         */

        $this->readConfigFile( "templates/" . strtolower( $this->TEMPLATE ) . "/config.json" );


        /*
         *  Add Skin Configuration
         */

        $this->readSkinConfigFiles( $this->SKIN );







        /*
         * Must Add Content Template Tag - This is common to all templates
         */
        $this->addEmbedTag( 'CONTENT', null );


        /*
         * Add a Tag for Each Configuration Setting
         * This allows you to use configuration settings within templates
         */
        foreach ( $this->_getProperties() as $name => $value ) {

            $this->addContentTag( $name, $value );

}

        /*
         * Add Powered By Tag
         */
        $this->addContentTag( 'POWERED_BY', '<a href="#"><span class="label">Powered by DownDraft &reg;</a></a>' );



    }

    /**
     * Config (internal)
     *
     * Configure Plugin - Use this to set defaults. 
     * @param none
     * @return void
     */
    private function _config() {

        /*
         * Set Defaults
         */
        $this->setConfig(
                'SKIN', 'bootstrap/2.3.2/default'
        );
        $this->setConfig(
                'TEMPLATE', 'fluid'
        );
        $this->setConfig( 'CONTENT_TAGS', null );




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

        if ( !$this->_content ){

            $tags = $this->_config_properties[ 'EMBED_TAGS' ];
            /*
             * Add a Tag only If one Doesn't Already Exist
             * This means that settings added by the user in Downcast.php will
             * override those that are added in config.php of the theme.
             * It also means that the user cannot override the settings created in _config()
             */
            if ( !isset( $tags[ $tag_name ] ) ){
                $tags[ $tag_name ] = $this->renderPage( $file_path, false );
        } else {

                //        echo '<br>' . $tag_name .' already set, not setting again';

        }
            $this->setConfig( 'EMBED_TAGS', $tags );


}

}

    protected $_content = null;

    /**
     * Constructor
     *
     * Class Constructor
     *
     * @param $content boolean Flags whether the instance is being used to parse content (true) or the main template file (false). 
     * @return void
     */
    public function __construct( $content = false ){
        $this->doActionHooks( '_dc_controller_start' );

        $this->_content = $content;

        $this->_config();
        $this->config();
        $this->_init();

    }

    /**
     * Render Page
     *
     * Renders a markdown page passed to it if markdown, otherwise serves it. 
     *
     * @param $file_path string The relative path to
     * @return void
     */
    public function renderPage( $url = null, $echo = true ) {


        //if no path given, assume its the request_uri
        if ( is_null( $url ) ){ $url = $_SERVER[ 'REQUEST_URI' ]; }

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


                    // echo ( $this->parseMarkdown( (file_get_contents( $file[ 'path' ] ) ) ));


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
             * Generate 404 if page does not exist
             */


            # header( "Location: " );
            //  header( "HTTP/1.0 404 Not Found" );
            header( "HTTP/1.0 404 Not Found" );


            return ( $this->parseMarkdown( ($this->renderPage( "errors/404/index.md?page_not_found=" . $url, false ) ) ));

            //  echo '<pre>', print_r( $this->EMBED_TAGS, true ), '</pre>';
            //  header( 'Location: /errors/404/' );
            //   $error_file = $this->joinPaths( $this->CONTENT_ROOT, "/404.md" );


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

        $path = ltrim( $path, '/' );
        return '/' . $path;

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
     * Render Template
     *
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

        //echo '<pre>', print_r( $this->EMBED_TAGS, true ), '</pre>';

        $tags = $this->CONTENT_TAGS;
        if ( !is_array( $tags ) ) {
            $tags = array();
}
        /*
         * Merge in the Embed Tags But Only If we are parsing the template (not the content)
         */
        if ( !$this->_content ){
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

            // $this->_config_properties[ 'EMBED_TAGS' ][ 'CONTENT' ] = $this->applyTagFilters( 'CONTENT', $this->_config_properties[ 'EMBED_TAGS' ][ 'CONTENT' ] );

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
     *
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
     * Read Configuration File
     *
     * Reads Configuration File and Sets Configuration
     *
     * @param none
     * @return void
     */
    public function readConfigFile( $config_file_path ) {
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

                $alternate_config_file = $this->joinPaths( $array_config[ 'CONFIG' ][ 'CONTENT_ROOT' ], "/config.json" );

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
            // echo '<pre>', print_r( $array_config, true ), '</pre>';
            if ( isset( $array_config[ 'CONFIG' ][ 'PLUGINS' ] ) ){
                /*
                 * Read their plugin file
                 */
                foreach ( $array_config[ 'CONFIG' ][ 'PLUGINS' ] as $key => $plugin_name ) {

                    $this->readConfigFile( $this->joinPaths( 'plugins', $plugin_name, "/config.json" ) );

                    $this->addPluginCSSandJs( $array_config[ 'CONFIG' ][ 'PLUGINS' ] );
                    /*
                     * Include their plugin file
                     */
                    $plugin_file = $this->joinPaths( 'plugins', $plugin_name, '/plugin.php' );

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
    public function addPluginCSSandJs( $plugins ) {

        $css_string;
        $js_string = '';




        if ( !is_array( $plugins ) ){

            $plugins = array( $plugins );
        }



        foreach ( $plugins as $plugin_key => $root_directory ) {
            $root_directory = strtolower( $root_directory );
            $config_file = $this->joinPaths( 'plugins/', $root_directory, "/config.json" );

            if ( file_exists( $config_file ) ) {
                $json_config = file_get_contents( $config_file );


                $array_config = json_decode( $json_config, true );
                if ( !is_array( $array_config[ 'PLUGIN_CSS' ] ) ){

                    $array_config[ 'PLUGIN_CSS' ] = array( $array_config[ 'PLUGIN_CSS' ] );
        }

                foreach ( $array_config[ 'PLUGIN_CSS' ] as $css_key => $link ) {

                    $link = $this->joinPaths( "/plugins/", $root_directory, $link );
                    $css_string .="\n<link href=\"$link\" rel=\"stylesheet\">";


}

                if ( !is_array( $array_config[ 'PLUGIN_JS' ] ) ){

                    $array_config[ 'PLUGIN_JS' ] = array( $array_config[ 'PLUGIN_JS' ] );
        }
                foreach ( $array_config[ 'PLUGIN_JS' ] as $js_key => $link ) {

                    $link = $this->joinPaths( "/plugins/", $root_directory, $link );
                    $js_string .="\n<script src=\"$link\"></script>";

}

}
}

        $this->addContentTag( "PLUGIN_CSS", $css_string );
        $this->addContentTag( "PLUGIN_JS", $js_string );

    }

    /**
     * Read Skin Configuration Files
     *
     * Reads the Skin Configuration Files Configured in the root config.json and creates the associated content tags
     *
     * @param $skin mixed The skin's relative path to its root directory. Either a string or an array of strings
     * @return void
     */
    public function readSkinConfigFiles( $skin_root_directories ) {

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

                    $link = $this->joinPaths( "/skins/", $skin_root_directory, $link );
                    $css_string .="\n<link href=\"$link\" rel=\"stylesheet\">";


}

                if ( !is_array( $array_skin_config[ 'JS' ] ) ){

                    $array_skin_config[ 'JS' ] = array( $array_skin_config[ 'JS' ] );
        }
                foreach ( $array_skin_config[ 'JS' ] as $js_key => $link ) {

                    $link = $this->joinPaths( "/skins/", $skin_root_directory, $link );
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

            $url=$url_info['path'];
        }
        //          echo 'File Info<pre>', print_r( $file_info, true ), '</pre>';
        //   echo 'Url Info<pre>', print_r( $url_info, true ), '</pre>';

        /*
         * Add a Content Tag for Each Query Variable so We can add them to Content if we want
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


          echo '<pre>';
          print_r( $url_info );

          print_r( $file_info );
          echo '</pre>';

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
            $file[ 'path' ] = realpath( ($this->joinPaths( $this->CONTENT_ROOT, $url_info[ 'path' ] ) ) );
} else //otherwise, if it ends in a slash, assume you want to serve index.md
        {

            /*
             *  Get File From URL
             * If URL Ends in Slash
             *  Look for a file in order of predence
             * This 
             * This array will be a number of possible files that will
             */


            if ( isset( $file_info[ 'extension' ] ) ){
                $default_files[] = ( realpath( $this->joinPaths( $this->CONTENT_ROOT, $file_info[ 'dirname' ], $file_info[ 'basename' ] ) ) );
}




            $default_files[] = ( realpath( $this->joinPaths( $this->CONTENT_ROOT, $file_info[ 'dirname' ], $file_info[ 'filename' ] . $this->MARKDOWN_EXTENSION ) ) );
//realpath




            $default_basenames = $this->INDEX_FILES;


            foreach ( $default_basenames as $key => $value ) {
                $default_files[] = ( realpath( $this->joinPaths( $this->CONTENT_ROOT, $url_info[ 'path' ] . $value ) ));
}
            $default_files = array_filter( $default_files );



            if ( empty( $default_files ) ){ //if array is empty, set it to false so we get a 404
                $file[ 'path' ] = false;
            } else {
                $file[ 'path' ] = realpath( array_shift( $default_files ) ); //get the file with the highest  precedence


                $file_info = pathinfo( $file[ 'path' ] );


                //    $file[ 'path' ] = realpath( ($this->joinPaths( $this->CONTENT_ROOT, $this->addTrailingSlash( $url_info[ 'path' ] ) ) . "index.md" ) );
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


                    $content = call_user_func( $callback, $tagname,$content );


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

    public function addPlugin( $plugin_name ) {


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

        $this->_action_hooks[ $hook_name ][] = $callback;


    }


}

?>