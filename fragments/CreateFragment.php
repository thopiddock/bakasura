<?php

/**
 * CreateFragment.php Created by PhpStorm.
 * User: tpidd
 * Date: 05/09/2015
 * Time: 02:08
 */
class CreateFragment implements IFragment, IEditor
{
    /**
     * @var int The page ID for the parent page.
     */
    private $pageId;

    /**
     * @return mixed
     */
    function getEditor()
    {
        ob_start();
        ?>
        <form id="fragment_createFragment" action="/action.php" method="post" xmlns="http://www.w3.org/1999/html">
            <fieldset class="stacked">
                <legend>Create a Fragment</legend>
                <label for="fragment_type">Fragment Type</label>
                <select title="Fragment Type"
                        name="fragment_type">
                    <option value="TextSection">Text Section</option>
                    <option value="ImageSection">Image Section</option>
                </select>
                <span>
                    <label for="fragment_header">Header</label>
                    <input type="text"
                           title="Header"
                           name="fragment_header"
                           value="">
                </span>
                <span>
                    <label for="fragment_content">Content</label>
                    <textarea title="Content"
                              name="fragment_content"></textarea>
                </span>

                <div style="display: none;">
                    <label for="fragment_media">Media</label>
                    <input type="file"
                           title="Media"
                           name="fragment_media"/>
                    <span id="progressbar"></span>
                </div>
                <input type="hidden" name="fragment_pageId" value="<?= $this->pageId ?>"/>
                <input type="hidden" name="fragment_index" value="<?= $this->index ?>"/>
                <input type="hidden" name="fragment_options" value=""/>
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </fieldset>
        </form>
        <script type="text/javascript">
            // Changing the style of the form based on fragment type
            $('#fragment_type').change(function (sel)
            {
                var $fragmentMedia = $('#fragment_media');
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

            // Uploader event
            $('#fragment_media').change(function ()
            {
                $('#fragment_createFragment').file({
                    url: 'upload.php',
                    secureuri: false,
                    fileElementId: 'uploadedfile',
                    dataType: 'json',
                    success: function (data, status)
                    {
                        if (typeof(data.error) != 'undefined')
                        {
                            if (data.error)
                            {
                                //print error
                                alert(data.error);
                            }
                            else
                            {
                                //clear
                                $('#img').find('img').attr('src', url + 'cache/' + data.msg);
                            }
                        }
                        $('#uploadedfile').change(function ()
                        {
                            ajaxFileUpload();
                        });
                    },
                    error: function (data, status, e)
                    {
                        //print error
                        alert(e);
                        $('#uploadedfile').change(function ()
                        {
                            ajaxFileUpload();
                        });
                    }
                });

                return false;
            });

            $("#fragment_createFragment").submit(function (event)
            {
                event.preventDefault();
                var form = $(this), url = form.attr("action");

                var type = form.find('select[name=fragment_type]').val();
                var json = {
                    'pageId': form.find('input[name=fragment_pageId]').val(),
                    'index': form.find('input[name=fragment_index]').val(),
                    'type': type,
                    'header': form.find('input[name=fragment_header]').val(),
                    'content': form.find('textarea[name=fragment_content]').val(),
                    'media': form.find('input[name=fragment_media]').val(),
                    'options': form.find('input[name=fragment_options]').val()
                };

                var posting = $.post(url, {
                    performer: type+'Fragment',
                    action: 'create',
                    vars: json
                });

                posting.done(function (data)
                {
                    var response = $.parseJSON(data);
                    if (response.success)
                    {
                        var inputIndex = $('input[name=fragment_index');
                        $(response.fragment).insertAt(inputIndex.val(), $('#content'));
                        //$("#result").append(data.returnHtml);
                        inputIndex.val(parseInt(inputIndex.val()) + 1);
                    }
                });
            });
        </script>
        <?php

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Gets the content for the fragment.
     *
     * @return mixed
     */
    function getContent()
    {
        return null;
    }

    /**
     * @param $pageId int The page ID for the parent page.
     * @param $index  int The index of the fragment.
     */
    public function __construct($pageId, $index)
    {
        $this->pageId = $pageId;
        $this->index = $index;
    }
}