<?php

/*
 * Templater DownCast Plugin
 * 
 * 
 * Redirect to a different template based on url
 * 
 * Usage: use the setTemplate() method to map a url to a template:
 *                $this->setTemplate(
             '/usage/' // Relative URL 
            ,'narrow' // Template Name
            );
 * 
 */

class Templater extends DowncastPlugin {


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
         * Make the default Template the same as configured one
         */
        $this->TEMPLATE_DEFAULT=$this->downcast()->CONFIG['SITE']['CONFIG']['TEMPLATE'];
       

/*
 * Map Templates to URL 
 * 
 * You must add trailing and leading slashes when there is no file extension in the url request.
 */
            $this->setTemplate(
             '/usage/' // Relative URL 
            ,'narrow' // Template Name
            );
    
        $this->setTemplate(
             '/' // Relative URL 
            ,'starter' // Template Name
            );
        

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
         * Add An Action Hook to Tell DownCast to Call Our Method Before the template is requested
         * 
         * 
         */

        $this->downcast()->addActionHook( '_dc_before_template', array( $this, 'changeTemplate' ) );






    }

  

    /**
     * Change Template
     *
     * Returns Text (Markdown is not parsed) but with line breaks replaced with br
     *
     * @param none
     * @return $text The parsed Text
     */
    public function changeTemplate(  ) {
        
                
        $page_info=$this->downcast()->getPageInfo();
           $url=$page_info['url'];
        $template_map=$this->TEMPLATE_MAP;
        if (in_array($url,array_keys($template_map))){
            
         $this->downcast()->TEMPLATE= $template_map[$url];   
        }else {
            
             $this->downcast()->TEMPLATE= $this->TEMPLATE_DEFAULT;   
            
        }
        
        

}
    /**
     * Set Template Path
     *
     * Sets the Template a URL should use
     *
     * @param $url string The url to which the template should be applied
     * @param $template_name string The template name
     * @return void
     */
    public function setTemplate( $url,$template_name ) {
                 $template_map=$this->TEMPLATE_MAP;
                 $template_map[$url]=$template_name;
                 $this->TEMPLATE_MAP=$template_map;

    }

}

?>
