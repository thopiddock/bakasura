/**
 * Created by tpidd on 29/09/2015.
 */
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