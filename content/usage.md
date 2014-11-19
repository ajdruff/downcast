##Usage

##About



##Turn your Markdown Files into a Website


With Downdraft:

* Create simple but beautiful single or multiple page websites
* Write content with Markdown
* Create themes as easily as creating an HTML page.
* Use our Twitter Bootstrap skins and templates, or easily create your own (with or without Bootstrap)


We made creating a website is as easy as uploading the Downdraft files to your website, choosing a template, and adding content by writing plain text Markdown files. No databases to worry about, no complicated configuration, and no complicated markup to learn.

Downdraft is built around Markdown, allowing you to easily write plain text documents in a natural way that it turns into beautiful web pages by using a few style sheets and a Markdown parser library.

Want to change the look and feel of your site? Try our our built-in templates or create your own. Creating a new template doesn't require learning a new template language - if you know how to write HTML or want to copy and paste an HTML page you already have, then go ahead and get busy. Just add the {CONTENT} tag in place of the content in the page - Downdraft will do the rest. If you need Sidebars, Navbars or other fancy stuff, they are easily added by just editing a few Markdown files. 


##File Naming and Urls

You can reach your content in the following ways:

Example 1 - File is Named `'my-post.md'` and is in directory `'content/subdirectory'`

* http://example.com/subdirectory/my-post.md
* http://example.com/subdirectory/my-post/

Example 2 - File is Named 'index.md' and is in directory 'content/subdirectory/my-post/

* http://example.com/subdirectory/my-post/index.md
* http://example.com/subdirectory/my-post/

##Url Order of Precedence

DownDraft will serve HTML, HTM, PHP , Markdown or any file that ends in any extension that your webserver supports. 

When it tries to resolve a URL to find the file that is should serve, it searches in the following ways:


1. **True File Paths** - A URL pointing to an actual file will be returned immediately without going through DownDraft's controller.
    * Example: http://example.com/content/index.md  returns /content/index.md Note, however that it will not be parsed for Markdown. In this example, index.md 




2. **URLS ending in a file extension** - If no actual file exists at the relative path for the Url being requested, DownDraft next gives precedence to URLs that end in file extensions. If your request ends in a file extension, DownDraft looks within the content folder for it.
    * Example: `http://example.com/index.md` will return the file in content/index.md
4. **True Directory Paths**
    * Example: `http://example.com/content/about/` will return the index file in `/content/about/` 
6. **Pretty Urls That do not contain a file extension** - These look like directory paths, but do not actually exist. DownDraft will look for files in this order:
    1. **Markdown Files with a name equal to the last directory name in the url**
        * Example: http://example.com/my-cool-post/  will parse and return markdown file /content/my-cool-post.md, if it exists, as html.
    3. **Default Files in Order Of Precedence**
        * You can configure the name and order of the default files in config.json file.
                
      
                {
                    "CONFIG": {
                        "INDEX_FILES": [
                            "index.php",
                            "index.html",
                            "index.htm",
                            "index.md"
                        ]
                
                }

        
        * If your INDEX_FILES configuration looked like the above, a request to http://example.com/my-cool-post/ would first look for `content/my-cool-post.md`, but if it didn't find it, would then look for `content/my-cool-post/index.php`,`index.html`,`index.htm`, and finally `index.md`











##Install and Configure

1. Choose your template
2. Choose your skin
3. Edit your content


##Content Tags


###Configuration Variables as Content Tags


###Query Variables as Content Tags


##Embed Tags



##Advanced Configuration

* Change your content folder
* Change the default index files
* Configure Multiple Content Folders Independently

##FAQs

How Do Add a Content Tag?

##Work on Multiple Websites Within a Single Installation of DownCast

By Using Multiple Content folders 

Example: You have a development site from which you are creating several websites.

* Website 1 - Create content directory content-website1
* Website 2 - Create content directory /content-webiste2

To switch between the two, change /json.config to point to the content directory that holds your website's files.




###Configuration Files Within a Multisite Install


All configuration data is stored in json.config either within the content directory or within the root installation directory.







##Themes
###Theme
* The difference between a Theme, Template and Skin
* How to Change your Template
* How to Change your
* Adding a 
* Using Query Variables as Content Tags
* Using Configuration Variables as Content Tags



##Examples

##How to 