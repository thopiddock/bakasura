<?php

/**
 * Interface IPage
 */
interface IPage
{
    /**
     * Get the title of the page.
     * @return string
     */
    function getTitle();

    /**
     * Get the pages short name, as used to define it's URL.
     * i.e. Return 'about' for http://example.com/about
     * @return mixed
     */
    function getShortName();

    /**
     * Get the page summary.
     * @return string
     */
    function getSummary();

    /**
     * Get the main content to display.
     * @return string
     */
    function getContent();

    /**
     * Get the style sheets for this page.
     * @return string | array | null
     */
    function getStyleSheets();

    /**
     * Get the scripts for this page.
     * @return string | array | null
     */
    function getScripts();
}