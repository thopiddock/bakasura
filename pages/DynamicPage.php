<?php

/**
 * Created by PhpStorm.
 * User: tpidd
 * Date: 25/08/2015
 * Time: 23:50
 */
class DynamicPage implements IPage, IEditor
{
    /**
     * @var $fragments IFragment[]
     */
    private $fragments;
    /**
     * The array containing the database information for the page.
     *
     * @var $page array
     */
    private $page;

    /**
     * Get the title of the page.
     *
     * @return string
     */
    function getTitle()
    {
        return $this->page != null ? $this->page['name'] : '';
    }

    /**
     * Get the page summary.
     *
     * @return string
     */
    function getSummary()
    {
        return $this->page != null ? $this->page['summary'] : '';
    }

    /**
     * Get the main content to display.
     *
     * @return string
     */
    function getContent()
    {
        $content = '';

        // If no fragments - display empty page.
        $fragmentCount = count($this->fragments);
        if ($fragmentCount == 0)
        {
            $textSectionFragment =
                new TextSectionFragment('No content here yet.', 'Someone should write something here...');
            $content .= $textSectionFragment->getContent();
        }
        else
        {
            foreach ($this->fragments as $fragment)
            {
                /* @var $fragment IFragment */
                if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor)
                {
                    if (in_array('IEditor', class_implements($fragment)))
                    {
                        /* @var $fragment IEditor|IFragment */
                        $content .= $fragment->getEditor();
                    }
                    else
                    {
                        $content .= $fragment->getContent();
                    }
                }
                else
                {
                    $content .= $fragment->getContent();
                }
            }
        }

        return $content;
    }

    /**
     * Get the style sheets for this page.
     *
     * @return string | array | null
     */
    function getStyleSheets()
    {
        return $this->page != null ? $this->page['styleLink'] : '';
    }

    /**
     * Get the scripts for this page.
     *
     * @return string | array | null
     */
    function getScripts()
    {
        return $this->page != null ? $this->page['scriptLink'] : '';
    }

    /**
     * Get the pages short name, as used to define it's URL.
     * i.e. Return 'about' for http://example.com/about
     *
     * @return mixed
     */
    function getShortName()
    {
        return $this->page != null ? $this->page['shortName'] : '';
    }

    /**
     * Get HTML to represent an editor section.
     *
     * @return string
     */
    function getEditor()
    {
        $createFragment = new CreateFragment($this->page['id'], count($this->fragments));

        return $createFragment->getEditor();
    }

    /**
     * Instantiates a new instance of the DynamicPage class.
     *
     * @param $databasePage array
     */
    public function __construct($databasePage)
    {
        $this->page = $databasePage;
        $this->fragments = [];

        // Return fragments from SQL
        $fragments = FragmentHandler::ReadPageFragments($this->page['id']);

        // Parse fragments
        if (count($fragments) > 0)
        {
            foreach ($fragments as $fragment)
            {
                $fragmentClass = $fragment['type'] . 'Fragment';
                if (class_exists($fragmentClass) && in_array('IDatabaseLoadable', class_implements($fragmentClass)))
                {
                    /* @@var $fragmentClass IDatabaseLoadable */
                    $this->fragments[] = $fragmentClass::generateFromDatabase($fragment);
                }
            }
        }
    }
}