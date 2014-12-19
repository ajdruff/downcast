    
if (typeof ViewSource=== 'undefined') {
 var ViewSource={};   
}

 ViewSource.Plugins = function() {   
    (function($) {
        $.fn.disableSelection = function() {
            return this
                    .attr('unselectable', 'on')
                    .css('user-select', 'none')
                    .on('selectstart', false);
        };
    })(jQuery);
    (function($) {
        $.fn.highlightjs = function() {
            $('pre code').each(function(i, block) {
                hljs.highlightBlock(block);
            });
        };
    })(jQuery);

 }
 
  ViewSource.Plugins();