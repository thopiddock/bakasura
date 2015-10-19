<?php

/**
 * CreatePage.php Created by PhpStorm.
 * User: tpidd
 * Date: 09/09/2015
 * Time: 21:54
 */
class CreatePage implements IPage, IEditor, IAction
{
    /**
     * @param $action
     * @param $vars
     *
     * @return bool
     */
    public static function processAction($action, $vars)
    {
        $success = false;
        $response = ['success' => $success];
        switch ($action) {
            case 'create':

                $success = DynamicPageHandler::CreatePage($vars['name'],
                    $vars['shortName'],
                    $vars['scriptLink'],
                    $vars['styleLink'],
                    null,
                    $vars['summary']);

                $response = ['success' => $success];
                break;
        }

        return $response;
    }

    /**
     * Get the main content to display.
     * @return string
     */
    function getContent()
    {
        header('HTTP/1.0 404 Not Found');

        $textSectionFragment = new TextSectionFragment('404 Error, nothing here yet.', 'Someone should create a page of something...');
        $content = $textSectionFragment->getContent();

        return $content;
    }

    /**
     * Get the title of the page.
     * @return string
     */
    function getTitle()
    {
        return 'Missing Page';
    }

    /**
     * Get the pages short name, as used to define it's URL.
     * i.e. Return 'about' for http://example.com/about
     * @return mixed
     */
    function getShortName()
    {
        return 'missing';
    }

    /**
     * Get the page summary.
     * @return string
     */
    function getSummary()
    {
        return 'The page you were looking for doesn\'t exist.';
    }

    /**
     * Get the style sheets for this page.
     * @return string | array | null
     */
    function getStyleSheets()
    {
        return null;
    }

    /**
     * Get the scripts for this page.
     * @return string | array | null
     */
    function getScripts()
    {
        return null;

    }

    /**
     * Get HTML to represent an editor section.
     * @return string
     */
    function getEditor()
    {
        ob_start();
        ?>
        <form id="page_createPage" action="/action.php" method="post">
            <fieldset class="stacked large">
                <legend>SUP BITCH, CREATE THE PAGE YO</legend>
                <label for="page_name">Page Name</label>
                <input type="text"
                       title="Page Name"
                       id="page_name"
                       name="page_name"
                       value="Page Name">
                <label for="page_shortName">Page Link (Short Name)</label>
                <input type="text"
                       title="Page Link (Short Name)"
                       id="page_shortName"
                       name="page_shortName"
                       value="<?= $this->selectedPageName ?>">
                <label for="page_scriptLink">Script Link</label>
                <input type="text"
                       title="Script Link"
                       id="page_scriptLink"
                       name="page_scriptLink"
                       value="">
                <label for="page_styleLink Link">Style Link</label>
                <input type="text"
                       title="Style Link"
                       id="page_styleLink"
                       name="page_styleLink"
                       value="">
                <label for="page_summary">Summary</label>
                <input type="text"
                       title="Summary"
                       id="page_summary"
                       name="page_summary"
                       value="">
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </fieldset>
        </form>
        <script type="text/javascript">
            $("#page_createPage").submit(function (event) {
                event.preventDefault();
                var form = $(this), url = form.attr("action");

                var json = {
                    'name': $('input[name=page_name]').val(),
                    'shortName': $('input[name=page_shortName]').val(),
                    'scriptLink': $('input[name=page_scriptLink]').val(),
                    'styleLink': $('input[name=page_styleLink]').val(),
                    'summary': $('input[name=page_summary]').val()
                };

                var posting = $.post(url, {
                    performer: 'CreatePage',
                    action: 'create',
                    vars: json
                });

                posting.done(function (data) {
                    alert('Page created!');
                    var response = $.parseJSON(data);
                    if (response.success) {
                        //$("#result").append(data.returnHtml);
                        location.reload();
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
     * Instantiates a new instance of the CreatePage class.
     *
     * @param $selectedPageName string
     */
    public function __construct($selectedPageName)
    {
        $this->selectedPageName = $selectedPageName;
        Site::$errorHandler->addError(new PageMissingError($this->selectedPageName));
    }
}