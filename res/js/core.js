function replaceAllSvgImages()
{
    /*
     * Replace all SVG images with inline SVG
     */
    $('img.svg').each(function ()
    {
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(imgURL, function (data)
        {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined')
            {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined')
            {
                $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');

    });
}
$(document).ready(function() {
    //checkScrollBars();
    $(window).on('resize', function() {
       
    });
    replaceAllSvgImages();

    /*
     * Add collapsible functionality to editor sections.
     */
    $('.fragment-container')
        .on('click', '.handle',
        function(event){
            console.log('test');
            var fragment = $(event.delegateTarget);
            var editing = (fragment.attr('data-editing') == "true");
            if(editing){
                fragment.children('.fragment-editor').slideUp('fast');
                fragment.children('.fragment-content').slideDown('fast');
            }else{

                fragment.children('.fragment-content').slideUp('fast');
                fragment.children('.fragment-editor').slideDown('fast');
            }
            fragment.attr('data-editing', !editing);
        }
    );
});

$.fn.insertAt = function(index, $parent) {
    return this.each(function() {
        if (index === 0) {
            $parent.prepend(this);
        } else {
            $parent.children().eq(index - 1).after(this);
        }
    });
}