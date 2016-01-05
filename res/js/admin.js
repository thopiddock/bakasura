/**
 * Created by tpidd on 29/09/2015.
 */
/*
$(function ()
{
    $("#content").sortable({
        scroll: true,
        handle: "h2,h3,h4,h5",
        containment: "#content",
        placeholder: "drag-placeholder",
        helper: "clone"
    }).disableSelection();
});
*/
function postTextFragmentEdit(event)
{
    event.preventDefault();
    var form = $(this), url = form.attr('action');
    var id = form.find('input[name=fragment_id]').val();

    var type = form.find('select[name=fragment_type]').val();
    var json = {
        'id': id,
        'index': form.find('input[name=fragment_index]').val(),
        'type': type,
        'header': form.find('input[name=fragment_header]').val(),
        'content': form.find('textarea[name=fragment_content]').val(),
        'media': form.find('input[name=fragment_media]').val(),
        'options': form.find('input[name=fragment_options]').val()
    };

    $.post(url, {
        performer: type + 'Fragment',
        action: 'update',
        vars: json
    }).done(function (data)
    {
        var response = $.parseJSON(data);
        if (response.success)
        {
            var fragment = $('.fragment-container[data-id=' + id + ']');
            var editing = (fragment.attr('data-editing') == "true");
            fragment.children('.fragment-editor').slideUp('fast', function ()
            {
                fragment.children('.fragment-content').replaceWith(response.fragment);
                fragment.attr('data-editing', !editing);
                replaceAllSvgImages();
            });
        }
    });
}

// Changing the style of the form based on fragment type
$('.fragment-editor form select[name=fragment_type]').on('change', function (sel)
{
    var $fragmentMedia = $(this).parent().find('input[name="fragment_media"]');
    switch (sel.target.value)
    {
        case 'TextSection' :
            $fragmentMedia.parent().hide('slow');
            break;
        case 'ImageSection':
            $fragmentMedia.parent().show('slow');
            break;
    }
});
