downcast
========

A Lightweight Markdown Web Publishing Platform




##Speed Suggestions

* use Google Pagespeed and act on suggestions - enable inline scripts where needed
* use firefox and firebug to check for javascript errors due to javascript libraries being loaded in wrong order.
* minimize css and javascript needed to load.
* javascript loading can be controlled by the order they appear in the configuration files
* place some scripts in footer where necessary
* caching: enabled, cleanup false unless need to refresh a page you updated.
* debugging off 
* use the sharedscripts plugin to ensure that shared javascript libraries are only loaded once. once a shared library is added to shared scripts, disable the library in the plugin that would otherwise have loaded it.
* the home page should load at <50 ms if caching is enabled ( not including scripts) . Even with page load , the entire page should be less than 1s. With caching disabled, onload is 1.17 s, with home page about 200 ms.
* minimize plugins
  