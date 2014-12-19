##ViewSource Plugin


<!-- image references -->
[example-page-rendered]: /plugins/ViewSource/content/img/page.png  "Example view of page with ViewSource plugin enabled"
[src-link]: /plugins/ViewSource/content/img/src-link.png  "Source Link Image"
[src-link-with-number]: /plugins/ViewSource/content/img/src-link-with-line-number-toggle.png   "Source Link Image with Line Numbering Toggle"
[example-page-source]: /plugins/ViewSource/content/img/page-source.png     "Example view of page's source code displayed via the ViewSource plugin"
###Description

Adds a 'View Source' tag beside a content element,that when clicked shows the source in a code block. Also allows you to create links to any source file that will show the source of that file.



### Activating the Plugin

Activate the plugin by using the Plugin Manager or by setting 'active' to true in the site's config.json file:


    "PLUGINS":
            {
                "ViewSource":
                        {"active": true}
            }

## Getting Started


By default, for security reasons, the ViewSource plugin doesn't show any source unless you change 'REVEAL_ALL' to true (see the next step) or add a link to a file's source. 


>Please read the security section before enabling REVEAL_ALL as it may reveal sensitive information on your site.
{: .alert .alert-error}


          
### Reveal All -  Enabling Content Element Source Links

If you do not have any sensitive information in your source files, and want to view each content element's source, you can set 'REVEAL_ALL' to true.

Edit the ViewSource.php file:

```  
        $this->REVEAL_ALL = true;
```  


 After enabling `REVEAL_ALL`,  you'll see a 'src' ![source link][src-link] link after each content element. 

![][example-page-rendered]
 {: .thumbnail}
 
 
 
 
 Click the ![source link][src-link] and the page will toggle between showing
 you the normal, or rendered, view,and the source code for the content.
 
 ![Image of Source Code View after src is clicked][example-page-source]
 {: .thumbnail}
 
###Source Code Line Numbering
 
 You will also see a '#' link ( ![source link with number][src-link-with-number] ) after the 'src' link. Click '#' link and your source code will update with line numbering. When you copy from source with line numbering, your paste will not include the line numbers.
 
###Source Code Links 

 To create a link the shows a file's source, add a `view_source` class to the link and set the `href` attribute to either the url to the file or the relative file path of the file.
 
 

*Example 1: Using the url*  

>The url `/plugins/ViewSource/help/` was created using DownCast's `addPage()` method to point to the file `/plugins/ViewSource/readme.md` . Creating a link that points to either `/plugins/ViewSource/help/` or  `/plugins/ViewSource/readme.md` will work, as long as 'view_source' is added as a class.
 
    [View Source{: .view_source}](/plugins/ViewSource/help/)

*Example 2: Using the file's relative path*   

 If we had created the link using the file path, it would look like: 
 
    [View Source{: .view_source}](/plugins/ViewSource/readme.md)

        
*Demo*

>In this demo, we have created a link to the ViewSource plugin's main class:

        [View Plugin Source{: .view_source}](/plugins/ViewSource/ViewSource.php)
         

To see the source code of the ViewSource.php file, click the 'View Plugin Source' link below, and if you are viewing this page with the ViewSource plugin activated, you'll see the source of the plugin's code appear just below the link.

[View Plugin Source{: .view_source}](/plugins/ViewSource/ViewSource.php)  

>You may only link to the same source file once on a page (including any content element source links). Linking to the same source file multiple times on the same page will result in 
unexpected behavior.
{: .alert .alert-info}


###White Listing

Sometimes you just want a few pages to contain the 'src' link, and don't want to add one manually yourself.

1. Disable content element source links

    ```
                $this->REVEAL_ALL=false;
    ```            
   
2. Add a {&#86;IEW_SOURCE} tag anywhere within your file's source except within a commment or a php block.
        
  
>Note that this will not add the link where the {&#86;IEW_SOURCE} tag is placed, but rather according to the VIEW_SOURCE_CONTENT_TEMPLATE setting within the config() method of ViewSource.php .
{: .alert .info}


###Security

Allowing anyone to see the source code for every content element in your page may be a security concern for you, especially if you are including passwords or other sensitive information in a php file that also is used to display output to the browser.

Be sure to use PHP's `include` statement to include your sensitive information from a file that is stored above the web root (outside of DownCast's main directory)

    
The ViewSource plugin will not reveal the contents of included files. 

So, instead of adding something like this to your main script:

	$database_user='user';
	$database_password='my-password';
        
Place that code in a separate file called something like 'passwords.php' and include it:

```  
    include 'passwords.txt'
    
```  

Place the file in a secure location above the web root, and configure your include path in your php.ini to include the directory. Avoid adding the path directly to the include statement since that will give an attacker a place to look.
    
    
>Although including sensitive information in a separate file does prevent the password information from being revealed, it still may provide clues to an attacker on how to hack into your site. Use the ViewSource plugin with caution - its recommended that you mainly use it for Markdown files with the .md extension since those files are not normally parsed for php and provides an attacker with limited information. Downcast will ignore PHP code within Markdown and other non-php files when the SAFE_PARSE option in the site's config.json file is set to true, the default.
{: .alert .alert-info}


###Syntax Highlighting

It's recommended that you add the highlightjs skin to enhance the formatting of the source code.
Highlightjs can also be configured in its config.json to show a wide variety of different formats.

Add the following to your core config.json file under the 'SKINS' section:

    "SKINS":
            {

                "highlightjs":
                        {"active": true, "path": "/highlightjs/8.3"}
            }

For more on Highlightjs, see the [highlightjs web site](https://highlightjs.org/)                            
                            
###Options

####Turn Off Content Element Source Links

You may not want the 'src' links that appear after each content element, but do want to manually link to a source file. To do this, edit `ViewSource.php` and turn off content element source links by setting `REVEAL_ALL` to false:

```
            $this->REVEAL_ALL=false;
```            
            
###Change the Layout of the Content Element Source Links

If you don't like the look of the 'src' content element link, edit the 'VIEW_SOURCE_LINK_TEMPLATE' in the config() method of `ViewSource.php'. 


###Change the Location of the Content Element Source Link

If you don't like where the default layout places the content source link ( i.e., you want to 
place the link in front of the content ), you can change the content template:

####Example 
 The following settings will change the 'src' text to 'Toggle Source', format the link as a label, and place it at the top of each content element.
 
```
            $this->VIEW_SOURCE_TEXT = 'Toggle Source';
           $this->VIEW_SOURCE_LINK_TEMPLATE = '<a class="view_source label"  title="view source@{RELATIVE_PATH}" href="{RELATIVE_PATH}">{VIEW_SOURCE_TEXT}</a>';
           $this->VIEW_SOURCE_CONTENT_TEMPLATE = '{VIEW_SOURCE_LINK}{CONTENT}';
```

####Troubleshooting 
>#####Links don't work after editing the VIEW_SOURCE_LINK_TEMPLATE
> * always include a 'view_source' as a class to your link  
> * always include href="{RELATIVE_PATH}"
>#####Some source links on a page do not show the source:
>* Never add multiple links to the same source file on the same page. Doing so may result in unpredictable behavior when more than one source file link is clicked.
>* Never link to the same source file as a content element appearing on thes same page. Doing so may result in unpredictable behavior when more than one source file link is clicked.
>* If you are trying to show a link to the same file where your link is placed, consider white listing the page. See the White Listing section for more information.

