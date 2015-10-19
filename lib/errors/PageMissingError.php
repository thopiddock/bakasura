<?php

/**
 * Class PageMissingError
 */
class PageMissingError extends Error
{
    public function __construct ($pageName)
    {
        parent::__construct('Could not resolve page : ' . $pageName, ErrorSeverityEnum::Error);
    }
}