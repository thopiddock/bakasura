<?php

class ComicPage implements IPage, IEditor
{
    private $comicPage;
    private $action;

    /**
     * @return string
     */
    public function getStyleSheets()
    {
        return 'plugins/comic/css/style.css';
    }

    public function getShortName()
    {
        return 'comic';
    }

    public function getTitle()
    {
        return 'Comic Page';
    }

    public function getSummary()
    {
        return 'I\'m the comic page';
    }

    public function getContent()
    {
        $content = '<section id="comic"><div id="comic_image">';

        if ($this->comicPage->prevPage)
        {
            $content .= '<a id="comic_prevOverlay" class="comic_navOverlay" href="' . $this->generatePageLinkHref($this->comicPage->prevPage) . '"></a>';
        }

        if ($this->comicPage->nextPage)
        {
            $content .= '<a href="' . $this->generatePageLinkHref($this->comicPage->nextPage) . '">';
            $content .= '<img src="' . $this->comicPage->image . '" />';
            $content .= '</a>';
        }
        else
        {
            $content .= '<img src="' . $this->comicPage->image . '" />';
        }

        $content .= '</div>';
        $content .= $this->getComicNavigation();
        $content .= '<header><h2>' . $this->comicPage->title . '</h2></header><p>' . $this->comicPage->description . '</p>';

        return $content;
    }

    /**
     * Get the scripts for this page.
     * @return string | array
     */
    function getScripts()
    {
        return null;
    }

    /**
     * Get HTML to represent an editor section.
     * @return string
     */
    public function getEditor()
    {
        $content = '<form id="comic_updateDetails" class="editable" action="index.php?v=comic" method="post">';
        $content .= '<fieldset class="joined">';
        $content .= '<legend>Edit this Comic Page</legend>';
        $content .= '<input type="text" placeholder="Enter the title" name="comic_title" value="' . $this->comicPage->title . '">';
        $content .= '<textarea placeholder="Enter the comic description" name="comic_description" rows="3">' . $this->comicPage->description . '</textarea>';
        $content .= '<button type="reset">Reset</button><button type="submit">Submit</button>';
        $content .= '</fieldset>';
        $content .= '<input type="hidden" name="comic_id" value="' . $this->comicPage->id . '">';
        $content .= '<input type="hidden" name="comic_image" value="' . $this->comicPage->image . '">';
        $content .= '<input type="hidden" name="comic_action" value="updateDetails">';
        $content .= '</form>';

        return $content;
    }

    private function generatePageLinkHref($page)
    {
        $href = 'comic';
        $href .= '?c=' . $page->chapter;
        $href .= '&p=' . $page->number;

        return $href;
    }

    private function getComicNavigation()
    {
        $content = $this->generatePageLink($this->comicPage->firstPage, 'First');
        $content .= $this->generatePageLink($this->comicPage->prevPage, 'Prev');
        $content .= $this->generatePageLink($this->comicPage->nextPage, 'Next');
        $content .= $this->generatePageLink($this->comicPage->lastPage, 'Last');

        return $content ? '<nav><ul class="horizontal-list">' . $content . '</ul></nav>' : null;
    }

    private function generatePageLink($page, $content)
    {
        $link = $page ? '<li>' : '<li class="inactive">';

        if ($page)
        {
            $link .= '<a href="' . $this->generatePageLinkHref($page) . '">';
        }

        $link .= '<span>' . $content . '</span>';

        if ($page)
        {
            $link .= '</a>';
        }

        $link .= '</li>';

        return $link;
    }

    private function processAction()
    {
        if (isset($_POST['comic_id']))
        {
            $comicId = $_POST['comic_id'];
        }
        else
        {
            $comicId = -1;
        }

        switch ($this->action)
        {
            case 'updateDetails':
                if (Site::GetAuthenticator()->getAuthenticationLevel() < AuthenticationLevelEnum::Editor)
                {
                    Site::$errorHandler->addError(new NotAuthorisedError('You are not allowed to edit this page.'));
                }
                else
                {
                    if ($comicId > -1)
                    {
                        $title       = isset($_POST['comic_title']) ? $_POST['comic_title'] : null;
                        $description = isset($_POST['comic_description']) ? $_POST['comic_description'] : null;
                        $image       = isset($_POST['comic_image']) ? $_POST['comic_image'] : null;

                        if (!ComicDatabaseHandler::UpdatePageDetails($comicId, $title, $description, $image))
                        {
                            Site::$errorHandler->addError(new SimpleError('Unable to update the comic page', ErrorSeverityEnum::Error));
                        }
                    }
                }

                break;
            case 'delete':
                if (Site::GetAuthenticator()->getAuthenticationLevel() < AuthenticationLevelEnum::Editor)
                {
                    Site::$errorHandler->addError(new NotAuthorisedError('You are not allowed to delete this page.'));
                }
                else
                {
                    if ($comicId > -1)
                    {
                        ComicDatabaseHandler::DeletePage($comicId);
                    }
                }
        }
    }

    public function __construct()
    {
        $selectedChapter = isset($_GET['c']) ? $_GET['c'] : null;
        $selectedPage    = isset($_GET['p']) ? $_GET['p'] : null;

        $this->action = isset($_POST['comic_action']) ? $_POST['comic_action'] : false;
        $this->processAction();

        if ($selectedPage == null)
        {
            if ($selectedChapter == null)
            {
                $latest          = ComicDatabaseHandler::ReadLatestPage();
                $selectedChapter = $latest['chapter'];
                $selectedPage    = $latest['number'];
            }
            else
            {
                $selectedPage = 0;
            }
        }

        $this->comicPage = new PageModel($selectedChapter, $selectedPage);
        $this->comicPage->initialise();
    }
}
