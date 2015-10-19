<?php

/**
 * Class ErrorPage
 */
class ErrorPage implements IPage
{
    /**
     * Get the development name for the page.
     * @return string
     */
    function getShortName()
    {
        return 'error';
    }

    /**
     * Get the style sheets for this page.
     * @return string | array
     */
    function getStyleSheets()
    {
        return null;
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
     * Get the main content to display.
     * @return string
     */
    function getContent()
    {
        return '';
    }

    /**
     * Get the title of the page.
     * @return string
     */
    function getTitle()
    {
        return 'Page doesn\'t exist';
    }

    /**
     * Get the page summary.
     * @return string
     */
    function getSummary()
    {
        return '<p>An error occurred trying to build a page.</p>';
    }
}