<?php

/**
 * Class PageMissingError
 */
class PageMissingError extends SimpleError
{
    public function __construct ($pageName)
    {
        parent::__construct('Could not resolve page : ' . $pageName, ErrorSeverityEnum::Error);
    }
}