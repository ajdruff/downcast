###Configuration Notes


####Configuration Files

#####Site Configuration

Site Configuration is specified in the config.json file in the root directory.

json files are structured text files that are easily digested by php and javascript. DownCast uses them quite extensively and they are easy to use once you get used to them. 

######INFO

The INFO section includes at least the following. 


NAME
:   Site Name
    Example 1: 
    "NAME:" "My Great Website"
    TAG: {SITE_NAME}
    

* NAME
* DOMAIN
* TAG_LINE
* DESCRIPTION
* COMMENT
* VERSION 

You may add your own. Each INFO tag will be converted to a CONTENT tag for which you can use inside your template. So, for example, 'NAME' becomes {SITE_NAME}. 

CONTENT_ROOT
:   The relative directory path to the diretory that holds your sites Markdown and other files.
    Example 1: 'content'
    
 
 SKIN
 :  The relative path or paths to the diretory that holds the skin's config.json file.
    Directories must be separated by a forward slash, '/' . 
    Path is relative to the website's root directory.
    Example 1: 
    
    Example 2: 
    
  