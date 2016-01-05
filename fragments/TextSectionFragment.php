<?php

/**
 * Created by PhpStorm.
 * User: tpidd
 * Date: 27/08/2015
 * Time: 13:54
 */
class TextSectionFragment extends BaseSectionFragment implements IFragment, IDatabaseLoadable, IEditor, IAction
{
    /**
     * @var string HTML content of the fragment.
     */
    private $text;
    /**
     * @var null string Header of the fragment.
     */
    private $header;
    /**
     * @var null int ID of the fragment if pulled from the database.
     */
    private $id;
    /**
     * @var null int Index of the fragment on a page.
     */
    private $index;

    /**
     * @param array $databaseValue The database content for a fragment.
     * @return TextSectionFragment
     */
    static function generateFromDatabase($databaseValue)
    {
        return new TextSectionFragment($databaseValue['content'],
            $databaseValue['id'],
            $databaseValue['index'],
            $databaseValue['header']);
    }

    /**
     * Process an action via GET/POST.
     *
     * @param $action string
     * @param $vars   string|array
     *
     * @return string Json string representing the response.
     */
    static function processAction($action, $vars)
    {
        $success = false;
        $response = ['success' => $success];
        switch ($action)
        {
            case 'create':
                self::processActionCreate($vars, $response);
                break;

            case 'update':
                self::processActionUpdate($vars, $response);
                break;
        }

        return $response;
    }

    /**
     * @return string
     */
    function getDisplayName()
    {
        return "Text Section";
    }

    /**
     * Get HTML to represent an editor section.
     *
     * @return string
     */
    function getEditor()
    {
        ob_start();
        ?>
        <div class="fragment-container" data-editing="false" data-id="<?= $this->id ?>">
            <div class="handle"><img class="svg" src="/res/img/icon_edit.svg" alt="Edit"/></div>
            <?= $this->getContent() ?>
            <section class="fragment-editor text-fragment" style="display: none;">
                <form id="fragment_editFragment_<?= $this->id ?>"
                      action="/action.php"
                      method="post"
                      xmlns="http://www.w3.org/1999/html">
                    <fieldset class="stacked">
                        <div style="position: relative; top :0 ; right:0; padding:4px;">#<?= $this->id ?></div>
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
                           id="fragment_header"
                           name="fragment_header"
                           value="<?= $this->header ?>">
                </span>
                <span>
                    <label for="fragment_content">Content</label>
                    <textarea title="Content"
                              id="fragment_content"
                              name="fragment_content"><?= $this->text ?></textarea>
                </span>

                        <div style="display: none;">
                            <label for="fragment_media">Media</label>
                            <input type="file"
                                   title="Media"
                                   name="fragment_media"/>
                            <span id="progressbar"></span>
                        </div>
                        <input type="hidden" name="fragment_id" value="<?= $this->id ?>"/>
                        <input type="hidden" name="fragment_index" value="<?= $this->index ?>"/>
                        <input type="hidden" name="fragment_options" value=""/>
                        <button type="reset">Reset</button>
                        <button type="submit">Update</button>
                    </fieldset>
                </form>
                <script type="text/javascript">
                    // Uploader event
                    $('fragment_editFragment_<?= $this->id ?> input[name="fragment_media"]').change(function ()
                    {
                        $('#fragment_editFragment_<?= $this->id ?>').file({
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

                    $("#fragment_editFragment_<?= $this->id ?>").submit(function (event)
                    {
                        postTextFragmentEdit.call(this, event, <?= $this->id ?>);
                    });
                </script>
            </section>
        </div>
        <?php

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @return string
     */
    function getContent()
    {
        $content = '<section class="fragment-content text-section">';

        if (isset($this->header))
        {
            $content .= '<h3>' . $this->header . '</h3>';
        }
        if (isset($this->text))
        {
            $content .= '<p>' . $this->text . '</p>';
        }

        $content .= '</section>';

        return $content;
    }

    /**
     * @param string        $text
     * @param int | null    $id
     * @param string | null $header
     */
    public function __construct($text, $id = null, $index = null, $header = null)
    {
        $this->text = $text;
        $this->header = $header;
        $this->index = $index;
        $this->id = $id;
    }
}